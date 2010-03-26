<?php
interface SomeOtherInterface {
	const FOOO = 'foo';
}
class BazTestFile implements SomeOtherInterface {
	
}
class BarTestFile extends BazTestFile {
	
}
class FooBarTestFile extends BarTestFile {
	
}
