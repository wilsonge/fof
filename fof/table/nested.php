<?php
/**
 * @package     FrameworkOnFramework
 * @subpackage  table
 * @copyright   Copyright (C) 2010 - 2014 Akeeba Ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// Protect from unauthorized access
defined('F0F_INCLUDED') or die;

/**
 * A class to manage tables holding nested sets (hierarchical data)
 *
 * @property int $lft Left value (for nested set implementation)
 * @property int $rgt Right value (for nested set implementation)
 * @property string $hash Slug hash (for faster searching)
 */
class F0FTableNested extends F0FTable
{
	/** @var int The level (depth) of this node in the tree */
	protected $treeDepth = null;

	/** @var F0FTableNested The root node in the tree */
	protected $treeRoot = null;

	/** @var F0FTableNested The parent node of ourselves */
	protected $treeParent = null;

	/** @var bool Should I perform a nested get (used to query ascendants/descendants) */
	protected $treeNestedGet = false;

	/** @var   array  A collection of custom, additional where clauses to apply during buildQuery */
	protected $whereClauses = array();

	/**
	 * Public constructor. Overrides the parent constructor, making sure there are lft/rgt columns which make it
	 * compatible with nested sets.
	 *
	 * @param   string           $table   Name of the database table to model.
	 * @param   string           $key     Name of the primary key field in the table.
	 * @param   JDatabaseDriver  &$db     Database driver
	 * @param   array            $config  The configuration parameters array
	 *
	 * @throws \RuntimeException When lft/rgt columns are not found
	 */
	public function __construct($table, $key, &$db, $config = array())
	{
		parent::__construct($table, $key, $db, $config);

		if (!$this->hasField('lft') || !$this->hasField('rgt'))
		{
			throw new \RuntimeException("Table $this->tableName is not compatible with F0FTableNested: it does not have lft/rgt columns");
		}
	}

	/**
	 * Overrides the automated table checks to handle the 'hash' column for faster searching
	 *
	 * @return boolean
	 */
	public function check()
	{
		// Create a slug if there is a title and an empty slug
		if ($this->hasField('title') && $this->hasField('slug') && empty($this->slug))
		{

			$this->slug = F0FStringUtils::toSlug($this->title);
		}

		// Create the SHA-1 hash of the slug for faster searching (make sure the hash column is CHAR(64) to take
		// advantage of MySQL's optimised searching for fixed size CHAR columns)
		if ($this->hasField('hash') && $this->hasField('slug'))
		{
			$this->hash = sha1($this->slug);
		}

		// Reset cached values
		$this->resetTreeCache();

		return parent::check();
	}

	/**
	 * Delete a node, either the currently loaded one or the one specified in $id. If an $id is specified that node
	 * is loaded before trying to delete it. In the end the data model is reset. If the node has any children nodes
	 * they will be removed before the node itself is deleted if $recursive == true (default: true).
	 *
	 * @param   integer $oid  		The primary key value of the item to delete
	 * @param   bool    $recursive  Should I recursively delete any nodes in the subtree? (default: true)
	 *
	 * @throws  UnexpectedValueException
	 *
	 * @return  boolean  True on success
	 */
	public function delete($oid = null, $recursive = true)
	{
		// Load the specified record (if necessary)
		if (!empty($oid))
		{
			$this->load($oid);
		}

		// Recursively delete all children nodes as long as we are not a leaf node and $recursive is enabled
		if ($recursive && !$this->isLeaf())
		{
			// Get a reference to the database
			$db = $this->getDbo();

			// Get my lft/rgt values
			$myLeft = $this->lft;
			$myRight = $this->rgt;

			$fldLft = $db->qn($this->getColumnAlias('lft'));
			$fldRgt = $db->qn($this->getColumnAlias('rgt'));

			// Get all sub-nodes
			// @todo
			$subNodes = $this->getClone();
			$subNodes->reset();
			$subNodes
				->whereRaw($fldLft . ' > ' . $fldLft)
				->whereRaw($fldRgt . ' < ' . $fldRgt)
				->get(true);

			// Delete all subnodes (goes through the model to trigger the observers)
			if (!empty($subNodes))
			{
				array_walk($subNodes, function($item, $key){
					/** @var F0FTableNested $item */
					$item->delete(null, false);
				});
			}
		}

		return parent::delete($oid);
	}

	/**
	 * Not supported in nested sets
	 *
	 * @param   string   $where  Ignored
	 *
	 * @return  void
	 *
	 * @throws  RuntimeException
	 */
	public function reorder($where = '')
	{
		throw new RuntimeException('reorder() is not supported by F0FTableNested');
	}

	/**
	 * Not supported in nested sets
	 *
	 * @param   integer  $delta  Ignored
	 * @param   string   $where  Ignored
	 *
	 * @return  void
	 *
	 * @throws  RuntimeException
	 */
	public function move($delta, $where = '')
	{
		throw new RuntimeException('move() is not supported by F0FTableNested');
	}

	/**
	 * Create a new record with the provided data. It is inserted as the last child of the current node's parent
	 *
	 * @param   array $data The data to use in the new record
	 *
	 * @return  static  The new node
	 */
	public function create($data)
	{
		$newNode = $this->getClone();
		$newNode->reset();
		$newNode->bind($data);

		if ($this->isRoot())
		{
			return $newNode->insertAsChildOf($this);
		}
		else
		{
			return $newNode->insertAsChildOf($this->getParent());
		}
	}

	/**
	 * Makes a copy of the record, inserting it as the last child of the current node's parent.
	 *
	 * @return static
	 */
	public function copy()
	{
		return $this->create($this->getData());
	}

	/**
	 * Method to reset class properties to the defaults set in the class
	 * definition. It will ignore the primary key as well as any private class
	 * properties.
	 *
	 * @return void
	 */
	public function reset()
	{
		$this->resetTreeCache();

		parent::reset();
	}

	/**
	 * Insert the current node as a tree root. It is a good idea to never use this method, instead providing a root node
	 * in your schema installation and then sticking to only one root.
	 *
	 * @return static
	 */
	public function insertAsRoot()
	{
		// @todo
	}

	/**
	 * Insert the current node as the first (leftmost) child of a parent node.
	 *
	 * WARNING: If it's an existing node it will be COPIED, not moved.
	 *
	 * @param F0FTableNested $parentNode The node which will become our parent
	 *
	 * @return $this for chaining
	 *
	 * @throws Exception
	 */
	public function insertAsFirstChildOf(F0FTableNested &$parentNode)
	{
		// @todo
	}

	/**
	 * Insert the current node as the last (rightmost) child of a parent node.
	 *
	 * WARNING: If it's an existing node it will be COPIED, not moved.
	 *
	 * @param F0FTableNested $parentNode The node which will become our parent
	 *
	 * @return $this for chaining
	 *
	 * @throws Exception
	 */
	public function insertAsLastChildOf(F0FTableNested &$parentNode)
	{
		// @todo
	}

	/**
	 * Alias for insertAsLastchildOf
	 *
	 * @param F0FTableNested $parentNode
	 *
	 * @return $this for chaining
	 *
	 * @throws Exception
	 */
	public function insertAsChildOf(F0FTableNested &$parentNode)
	{
		return $this->insertAsLastChildOf($parentNode);
	}

	/**
	 * Insert the current node to the left of (before) a sibling node
	 *
	 * WARNING: If it's an existing node it will be COPIED, not moved.
	 *
	 * @param F0FTableNested $siblingNode We will be inserted before this node
	 *
	 * @return $this for chaining
	 *
	 * @throws Exception
	 */
	public function insertLeftOf(F0FTableNested &$siblingNode)
	{
		// @todo
	}

	/**
	 * Insert the current node to the right of (after) a sibling node
	 *
	 * WARNING: If it's an existing node it will be COPIED, not moved.
	 *
	 * @param F0FTableNested $siblingNode We will be inserted after this node
	 *
	 * @return $this for chaining
	 * @throws Exception
	 */
	public function insertRightOf(F0FTableNested &$siblingNode)
	{
		// @todo
	}

	/**
	 * Alias for insertRightOf
	 *
	 * @param F0FTableNested $siblingNode
	 *
	 * @return $this for chaining
	 */
	public function insertAsSiblingOf(F0FTableNested &$siblingNode)
	{
		return $this->insertRightOf($siblingNode);
	}

	/**
	 * Move the current node (and its subtree) one position to the left in the tree, i.e. before its left-hand sibling
	 *
	 * @return $this
	 */
	public function moveLeft()
	{
		// @todo
	}

	/**
	 * Move the current node (and its subtree) one position to the right in the tree, i.e. after its right-hand sibling
	 *
	 * @return $this
	 */
	public function moveRight()
	{
		// @todo
	}

	/**
	 * Moves the current node (and its subtree) to the left of another node. The other node can be in a different
	 * position in the tree or even under a different root.
	 *
	 * @param F0FTableNested $siblingNode
	 *
	 * @return $this for chaining
	 *
	 * @throws Exception
	 */
	public function moveToLeftOf(F0FTableNested $siblingNode)
	{
		// @todo
	}

	/**
	 * Moves the current node (and its subtree) to the right of another node. The other node can be in a different
	 * position in the tree or even under a different root.
	 *
	 * @param F0FTableNested $siblingNode
	 *
	 * @return $this for chaining
	 *
	 * @throws \Exception
	 */
	public function moveToRightOf(F0FTableNested $siblingNode)
	{
		// @todo
	}

	/**
	 * Alias for moveToRightOf
	 *
	 * @param F0FTableNested $siblingNode
	 *
	 * @return $this for chaining
	 */
	public function makeNextSiblingOf(F0FTableNested $siblingNode)
	{
		return $this->moveToRightOf($siblingNode);
	}

	/**
	 * Alias for makeNextSiblingOf
	 *
	 * @param F0FTableNested $siblingNode
	 *
	 * @return $this for chaining
	 */
	public function makeSiblingOf(F0FTableNested $siblingNode)
	{
		return $this->makeNextSiblingOf($siblingNode);
	}

	/**
	 * Alias for moveToLeftOf
	 *
	 * @param F0FTableNested $siblingNode
	 *
	 * @return $this for chaining
	 */
	public function makePreviousSiblingOf(F0FTableNested $siblingNode)
	{
		return $this->moveToLeftOf($siblingNode);
	}

	/**
	 * Moves a node and its subtree as a the first (leftmost) child of $parentNode
	 *
	 * @param F0FTableNested $parentNode
	 *
	 * @return $this for chaining
	 *
	 * @throws Exception
	 */
	public function makeFirstChildOf(F0FTableNested $parentNode)
	{
		// @todo
	}

	/**
	 * Moves a node and its subtree as a the last (rightmost) child of $parentNode
	 *
	 * @param F0FTableNested $parentNode
	 *
	 * @return $this for chaining
	 *
	 * @throws Exception
	 */
	public function makeLastChildOf(F0FTableNested $parentNode)
	{
		// @todo
	}

	/**
	 * Alias for makeLastChildOf
	 *
	 * @param F0FTableNested $parentNode
	 *
	 * @return $this for chaining
	 */
	public function makeChildOf(F0FTableNested $parentNode)
	{
		return $this->makeLastChildOf($parentNode);
	}

	/**
	 * Makes the current node a root (and moving its entire subtree along the way). This is achieved by moving the node
	 * to the right of its root node
	 *
	 * @return  $this  for chaining
	 */
	public function makeRoot()
	{
		// @todo
	}

	/**
	 * Gets the level (depth) of this node in the tree. The result is cached in $this->treeDepth for faster retrieval.
	 *
	 * @return int|mixed
	 */
	public function getLevel()
	{
		// @todo
	}

	/**
	 * Returns the immediate parent of the current node
	 *
	 * @return static
	 */
	public function getParent()
	{
		// @todo
	}

	/**
	 * Is this a top-level root node?
	 *
	 * @return bool
	 */
	public function isRoot()
	{
		// @todo
	}

	/**
	 * Is this a leaf node (a node without children)?
	 *
	 * @return bool
	 */
	public function isLeaf()
	{
		return ($this->rgt - 1) == $this->lft;
	}

	/**
	 * Is this a child node (not root)?
	 *
	 * @return bool
	 */
	public function isChild()
	{
		return !$this->isRoot();
	}

	/**
	 * Returns true if we are a descendant of $otherNode
	 *
	 * @param F0FTableNested $otherNode
	 *
	 * @return bool
	 */
	public function isDescendantOf(F0FTableNested $otherNode)
	{
		// @todo
	}

	/**
	 * Returns true if $otherNode is ourselves or if we are a descendant of $otherNode
	 *
	 * @param F0FTableNested $otherNode
	 *
	 * @return bool
	 */
	public function isSelfOrDescendantOf(F0FTableNested $otherNode)
	{
		return $otherNode->equals($this) || $this->isDescendantOf($otherNode);
	}

	/**
	 * Returns true if we are an ancestor of $otherNode
	 *
	 * @param F0FTableNested $otherNode
	 *
	 * @return bool
	 */
	public function isAncestorOf(F0FTableNested $otherNode)
	{
		// @todo
	}

	/**
	 * Returns true if $otherNode is ourselves or we are an ancestor of $otherNode
	 *
	 * @param F0FTableNested $otherNode
	 *
	 * @return bool
	 */
	public function isSelfOrAncestorOf(F0FTableNested $otherNode)
	{
		return $otherNode->equals($this) || $this->isAncestorOf($otherNode);
	}

	/**
	 * Is $node this very node?
	 *
	 * @param F0FTableNested $node
	 *
	 * @return bool
	 */
	public function equals(F0FTableNested &$node)
	{
		return (
			($this->getId() == $node->getId())
			&& ($this->lft == $node->lft)
			&& ($this->rgt == $node->rgt)
		);
	}

	/**
	 * Checks if our node is inside the subtree of $otherNode. This is a fast check as only lft and rgt values have to
	 * be compared.
	 *
	 * @param F0FTableNested $otherNode
	 *
	 * @return bool
	 */
	public function insideSubtree(F0FTableNested $otherNode)
	{
		return ($this->lft > $otherNode->lft) && ($this->rgt < $otherNode->rgt);
	}

	/**
	 * Returns true if both this node and $otherNode are root, leaf or child (same tree scope)
	 *
	 * @param F0FTableNested $otherNode
	 *
	 * @return bool
	 */
	public function inSameScope(F0FTableNested $otherNode)
	{
		if ($this->isLeaf())
		{
			return $otherNode->isLeaf();
		}
		elseif ($this->isRoot())
		{
			return $otherNode->isRoot();
		}
		elseif ($this->isChild())
		{
			return $otherNode->isChild();
		}
		else
		{
			return false;
		}
	}

	/**
	 * get() will return all ancestor nodes and ourselves
	 *
	 * @return void
	 */
	protected function scopeAncestorsAndSelf()
	{
		// @todo
	}

	/**
	 * get() will return all ancestor nodes but not ourselves
	 *
	 * @return void
	 */
	protected function scopeAncestors()
	{
		// @todo
	}

	/**
	 * get() will return all sibling nodes and ourselves
	 *
	 * @return void
	 */
	protected function scopeSiblingsAndSelf()
	{
		// @todo
	}

	/**
	 * get() will return all sibling nodes but not ourselves
	 *
	 * @return void
	 */
	protected function scopeSiblings()
	{
		// @todo
	}

	/**
	 * get() will return only leaf nodes
	 *
	 * @return void
	 */
	protected function scopeLeaves()
	{
		// @todo
	}

	/**
	 * get() will return all descendants (even subtrees of subtrees!) and ourselves
	 *
	 * @return void
	 */
	protected function scopeDescendantsAndSelf()
	{
		// @todo
	}

	/**
	 * get() will return all descendants (even subtrees of subtrees!) but not ourselves
	 *
	 * @return void
	 */
	protected function scopeDescendants()
	{
		// @todo
	}

	/**
	 * get() will only return immediate descendants (first level children) of the current node
	 *
	 * @return void
	 */
	protected function scopeImmediateDescendants()
	{
		// @todo
	}

	/**
	 * get() will not return the selected node if it's part of the query results
	 *
	 * @param F0FTableNested $node The node to exclude from the results
	 *
	 * @return void
	 */
	public function withoutNode(F0FTableNested $node)
	{
		// @todo
	}

	/**
	 * get() will not return ourselves if it's part of the query results
	 *
	 * @return void
	 */
	protected function scopeWithoutSelf()
	{
		$this->withoutNode($this);
	}

	/**
	 * get() will not return our root if it's part of the query results
	 *
	 * @return void
	 */
	protected function scopeWithoutRoot()
	{
		$rootNode = $this->getRoot();
		$this->withoutNode($rootNode);
	}

	/**
	 * Returns the root node of the tree this node belongs to
	 *
	 * @return static
	 *
	 * @throws \RuntimeException
	 */
	public function getRoot()
	{
		// @todo
	}

	/**
	 * Get all ancestors to this node and the node itself. In other words it gets the full path to the node and the node
	 * itself.
	 *
	 * @return F0FDatabaseIterator
	 */
	public function getAncestorsAndSelf()
	{
		// @todo
	}

	/**
	 * Get all ancestors to this node and the node itself, but not the root node. If you want to
	 *
	 * @return F0FDatabaseIterator
	 */
	public function getAncestorsAndSelfWithoutRoot()
	{
		// @todo
	}

	/**
	 * Get all ancestors to this node but not the node itself. In other words it gets the path to the node, without the
	 * node itself.
	 *
	 * @return F0FDatabaseIterator
	 */
	public function getAncestors()
	{
		// @todo
	}

	/**
	 * Get all ancestors to this node but not the node itself and its root.
	 *
	 * @return F0FDatabaseIterator
	 */
	public function getAncestorsWithoutRoot()
	{
		// @todo
	}

	/**
	 * Get all sibling nodes, including ourselves
	 *
	 * @return F0FDatabaseIterator
	 */
	public function getSiblingsAndSelf()
	{
		// @todo
	}

	/**
	 * Get all sibling nodes, except ourselves
	 *
	 * @return F0FDatabaseIterator
	 */
	public function getSiblings()
	{
		// @todo
	}

	/**
	 * Get all leaf nodes in the tree. You may want to use the scopes to narrow down the search in a specific subtree or
	 * path.
	 *
	 * @return F0FDatabaseIterator
	 */
	public function getLeaves()
	{
		// @todo
	}

	/**
	 * Get all descendant (children) nodes and ourselves.
	 *
	 * Note: all descendant nodes, even descendants of our immediate descendants, will be returned.
	 *
	 * @return F0FDatabaseIterator
	 */
	public function getDescendantsAndSelf()
	{
		// @todo
	}

	/**
	 * Get only our descendant (children) nodes, not ourselves.
	 *
	 * Note: all descendant nodes, even descendants of our immediate descendants, will be returned.
	 *
	 * @return F0FDatabaseIterator
	 */
	public function getDescendants()
	{
		// @todo
	}

	/**
	 * Get the immediate descendants (children). Unlike getDescendants it only goes one level deep into the tree
	 * structure. Descendants of descendant nodes will not be returned.
	 *
	 * @return F0FDatabaseIterator
	 */
	public function getImmediateDescendants()
	{
		// @todo
	}

	/**
	 * Returns a hashed array where each element's key is the value of the $key column (default: the ID column of the
	 * table) and its value is the value of the $column column (default: title). Each nesting level will have the value
	 * of the $column column prefixed by a number of $separator strings, as many as its nesting level (depth).
	 *
	 * This is useful for creating HTML select elements showing the hierarchy in a human readable format.
	 *
	 * @param string $column
	 * @param null   $key
	 * @param string $seperator
	 *
	 * @return array
	 */
	public function getNestedList($column = 'title', $key = null, $seperator = '  ')
	{
		// @todo
	}

	/**
	 * Locate a node from a given path, e.g. "/some/other/leaf"
	 *
	 * Notes:
	 * - This will only work when you have a "slug" and a "hash" field in your table.
	 * - If the path starts with "/" we will use the root with lft=1. Otherwise the first component of the path is
	 *   supposed to be the slug of the root node.
	 * - If the root node is not found you'll get null as the return value
	 * - You will also get null if any component of the path is not found
	 *
	 * @param string $path The path to locate
	 *
	 * @return F0FTableNested|null The found node or null if nothing is found
	 */
	public function findByPath($path)
	{
		// @todo
	}

	public function isValid()
	{
		// @todo
	}

	public function rebuild()
	{
		// @todo
	}

	/**
	 * Resets cached values used to speed up querying the tree
	 *
	 * @return  static  for chaining
	 */
	protected function resetTreeCache()
	{
		$this->treeDepth = null;
		$this->treeRoot = null;
		$this->treeParent = null;
		$this->treeNestedGet = false;

		return $this;
	}

	/**
	 * Add custom, pre-compiled WHERE clauses for use in buildQuery. The raw WHERE clause you specify is added as is to
	 * the query generated by buildQuery. You are responsible for quoting and escaping the field names and data found
	 * inside the WHERE clause.
	 *
	 * @param   string  $rawWhereClause  The raw WHERE clause to add
	 *
	 * @return  $this  For chaining
	 */
	public function whereRaw($rawWhereClause)
	{
		$this->whereClauses[] = $rawWhereClause;

		return $this;
	}

	/**
	 * Builds the query for the get() method
	 *
	 * @return JDatabaseQuery
	 */
	protected function buildQuery()
	{
		$db = $this->getDbo();

		$query = $db->getQuery(true)
			->select($db->qn('node') . '.*')
			->from($db->qn($this->tableName) . ' AS ' . $db->qn('node'));

		if ($this->treeNestedGet)
		{
			$query
				->join('CROSS', $db->qn($this->tableName) . ' AS ' . $db->qn('parent'));
		}

		// Apply custom WHERE clauses
		if (count($this->whereClauses))
		{
			foreach ($this->whereClauses as $clause)
			{
				$query->where($clause);
			}
		}

		return $query;
	}

	/**
	 * Returns a database iterator to retrieve records. Use the scope methods and the whereRaw method to define what
	 * exactly will be returned.
	 *
	 * @param   integer $limitstart     How many items to skip from the start, only when $overrideLimits = true
	 * @param   integer $limit          How many items to return, only when $overrideLimits = true
	 *
	 * @return  F0FDatabaseIterator  The data collection
	 */
	public function get($limitstart = 0, $limit = 0)
	{
		$limitstart = max($limitstart, 0);
		$limit = max($limit, 0);

		$query = $this->buildQuery();
		$db = $this->getDbo();
		$db->setQuery($query, $limitstart, $limit);
		$cursor = $db->execute();

		$dataCollection = F0FDatabaseIterator::getIterator($db->name, $cursor, null, $this->config['_table_class']);

		return $dataCollection;
	}
}