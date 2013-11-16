<?php

class FtestFakeModel {
	
	public function getName()
	{
		return '';
	}
}

class FtestFakeModel2 extends FtestFakeModel {
	
	public function getTable()
	{
		return false;
	}
}