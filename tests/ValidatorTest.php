<?php

use PHPUnit\Framework\TestCase;

use FormGuide\PHPFormValidator\Validators;

class ValidatorTest extends TestCase
{

	private function assertValidationFails($validation, $postdata, $value='')
	{
		$val = Validators::create('field');
		$res = $val->$validation($postdata);
		$this->assertFalse($res);
		$this->assertTrue($val->hasErrors());
		$this->assertEquals(1, $val->getErrorCount());		
	}

	private function assertValidationSucceeds($validation, $postdata, $value='')
	{
		$val = Validators::create('field');
		$res = $val->$validation($postdata);
		$this->assertTrue($res);
		$this->assertFalse($val->hasErrors());
		$this->assertEquals(0, $val->getErrorCount());
	}	

	public function testRequiredFails()
	{
		/*$val = Validators::create('name');

		$res = $val->required(array('name'=>'')  );
		$this->assertFalse($res);
		$this->assertTrue($val->hasErrors());
		$this->assertEquals(1, $val->getErrorCount());*/
		$this->assertValidationFails('required',[]);

		$this->assertValidationFails('required',['field'=>'']);
		$this->assertValidationFails('required',['field'=>'  ']);
	}

	public function testRequiredSuccess()
	{
		$val = Validators::create('name');
		$res = $val->required(array('name'=>'someone')  );
		$this->assertTrue($res);
		$this->assertFalse($val->hasErrors());		
	}

	public function testMaxLengthFails()
	{
		$val = Validators::create('name');
		$res = $val->maxlen(array('name'=>'someone12345'),['value'=>10]  );
		$this->assertFalse($res);
		$this->assertTrue($val->hasErrors());		
	}

	public function testMaxLengthSuccess()
	{
		$val = Validators::create('name');
		$res = $val->maxlen(array('name'=>'someone'),['value'=>10]  );
		$this->assertTrue($res);
		$this->assertFalse($val->hasErrors());		
	}

	public function testEmailFails()
	{
		/*$val = Validators::create('emailfield');
		$res = $val->maxlen(array('emailfield'=>'plaintext'),['value'=>10] );		
		$this->assertTrue($res);
		$this->assertFalse($val->hasErrors());	*/

		$this->assertValidationFails('email',['field'=>'plaintext']);
		$this->assertValidationFails('email',['field'=>'pl.ain.text']);
	}

	public function testEmailSucceeds()
	{
		$this->assertValidationSucceeds('email',['field'=>'someone@somewhere.com']);
		$this->assertValidationSucceeds('email',['field'=>'someone@somewhere.c']);
		$this->assertValidationSucceeds('email',['field'=>'someone@somewhere.domain']);
		$this->assertValidationSucceeds('email',['field'=>'someone@sub.somewhere.domain']);
		$this->assertValidationSucceeds('email',['field'=>'some.one@somewhere.domain']);
		$this->assertValidationSucceeds('email',['field'=>'some__one@somewhere.domain']);
	}
	
}