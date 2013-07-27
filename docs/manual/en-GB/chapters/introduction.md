Introduction
============

What is FOF?
------------

FOF (Framework on Framework) is a rapid application development
framework for Joomla!. Unlike other frameworks it is not standalone. It
extends the Joomla! Platform instead of replacing it, featuring its own
forked and extended version of the MVC classes, keeping a strong
semblance to the existing Joomla! MVC API. This means that you don't
have to relearn writing Joomla! extensions. Instead, you can start being
productive from the first day you're using it. Our goal is to always
support the officially supported LTS versions of Joomla! and not break
backwards compatibility without a clear deprecation and migration path.

FOF is compatible with the database technologies used by Joomla! itself: MySQL, SQL Server (and Windows Azure SQL), PostgreSQL. In most cases you can write a component in one database server technology and have it run on the other database server technologies with minimal or no effort.

FOF is currently used by free and commercial components for Joomla! by an increasing number of developers.

Free Software means collaboration
---------------------------------

The reason of existence of FOSS (Free and Open Source Software) is
collaboration between developers. FOF is no exception; it exists because
and for the community of Joomla! developers. It is provided free of
charge and with all of the freedoms of the GPL for you to benefit. And
in true Free Software spirit, the community aspect is very strong.
Participating is easy and fun.

If you want to discuss FOF there is [a Google Groups mailing
list](https://groups.google.com/forum/?hl=en&fromgroups#!forum/frameworkonframework).
This is a peer discussion group where developers working with FOF can
freely discuss.

If you have a feature proposal or have found a bug, but you're not sure
how to code it yourself, please report it on the list.

If you have a patch feel free to fork [this project on
GitHub](https://github.com/akeeba/fof) (you only need a free account to
do that) and send a pull request. Please remember to describe what you
intended to achieve to help me review your code faster.

If you've found a cool hack (in the benign sense of the word, not the
malicious one...), something missing from the documentation or have a
tip which can help other developers feel free to edit the Wiki. We're
all grown-ups and professionals, I believe there is no need of policing
the wiki edits. If you're unsure about whether a wiki edit is
appropriate, please ask on the list.

Preface to this documentation
-----------------------------

FOF is a rapid application development framework for the Joomla! CMS.
Instead of trying to completely replace Joomla!’s own API (formerly
known as the Joomla! Platform) it builds upon it and extends it both in
scope and features. In the end of the day it enables agony-free
extension development for the Joomla! CMS.

In order to exploit the time-saving capabilities of the FOF framework to
the maximum you need to understand how it's organized, the conventions
used and how its different pieces work together. This documentation
attempts to provide you with this knowledge.

As with every piece of documentation we had to answer two big questions:
where do we start and how do we structure the content. The first
question was easy to answer. Having given the presentation of the FOF
framework countless times we have developed an intuitive grasp of how to
start presenting it: from the abstract to the concrete.

The second question was harder to answer. Do we write a dry reference to
the framework or more of a story-telling documentation which builds up
its reader’s knowledge? Since we are all developers we can read the code
(and DocBlocks), meaning that the first option is redundant. Therefore
we decided to go for the second option.

As a result this documentation does not attempt to be a complete
reference, a development gospel, the one and only source of information
on FOF. On the contrary, this documentation aims to be the beginning of
your journey, much like a travel guide. What matters the most is the
journey itself, writing your own extensions based on FOF. As you go on
writing software you will be full of questions. Most of them you’ll
answer yourself. Some of them will be already answered in the wiki. A
few of them you’ll have to ask on the mailing list. In the end of the
day you will be richer in knowledge. If you do dig up a golden nugget of
knowledge, please do consider writing a wiki page. This way we’ll all be
richer and enjoy our coding trip even more.

Have fun and code on!