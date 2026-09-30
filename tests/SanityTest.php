<?php
use PHPUnit\Framework\TestCase;

// Check sanity, CI Placeholder test before real tests exist
class SanityTest extends TestCase {

	//Confirms the test runner is wired up.
	public function test_phpunit_is_running(): void {
		$this->assertTrue( true );
	}
}
