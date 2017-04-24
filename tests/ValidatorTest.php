<?php

use PHPUnit\Framework\TestCase;

use FormGuide\PHPFormValidator\Validators;

class ValidatorTest extends TestCase
{

    private function assertValidationFails($validation, $postdata, $value='')
    {
        $val = Validators::create('field');
        $res = $val->$validation($postdata, ['value'=>$value]);
        $this->assertFalse($res);
        $this->assertTrue($val->hasErrors());
        $this->assertEquals(1, $val->getErrorCount());      
    }

    private function assertValidationSucceeds($validation, $postdata, $value='')
    {
        $val = Validators::create('field');
        $res = $val->$validation($postdata, ['value'=>$value] );
        $this->assertTrue($res);
        $this->assertFalse($val->hasErrors());
        $this->assertEquals(0, $val->getErrorCount());
    }   

    public function testRequiredFails()
    {
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
        $this->assertValidationFails('maxlen',['field'=>'someone12345'],10);
        $this->assertValidationFails('maxlen',['field'=>'1234567890'],6);
    }

    public function testMaxLengthSuccess()
    {
        $this->assertValidationSucceeds('maxlen',['field'=>'1234567890'],15);

        $this->assertValidationSucceeds('maxlen',[],15);
    }

    public function testEmailFails()
    {
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

    public function testMinLengthFails()
    {
        $this->assertValidationFails('minlen',['field'=>'plaintext'], 10);
    }
    
    public function testMinLengthSucceeds()
    {
        $this->assertValidationSucceeds('minlen',[], 10);
    }    

    public function testAlphabeticFails()
    {
        $this->assertValidationFails('alphabetic',['field'=>'sometest123']);   
        $this->assertValidationFails('alphabetic',['field'=>'sometest with space']);
        $this->assertValidationFails('alphabetic',['field'=>'sometest_space']);

    }

    public function testAlphabeticSucceeds()
    {
        $this->assertValidationSucceeds('alphabetic',['field'=>'sometest']);   
        $this->assertValidationSucceeds('alphabetic',['field'=>'sometestCAPS']);
    }

    public function testAlphaNumericFails()
    {
        $this->assertValidationFails('alphanumeric',['field'=>'sometest.123']);   
        $this->assertValidationFails('alphanumeric',['field'=>'sometest123 with space']);
        $this->assertValidationFails('alphanumeric',['field'=>'sometest_space']);
    }

    public function testAlphaNumericSucceeds()
    {
        $this->assertValidationSucceeds('alphanumeric',['field'=>'sometest']);   
        $this->assertValidationSucceeds('alphanumeric',['field'=>'3sometest123']);
    }    

    public function testAlphabeticSpace()
    {
        $this->assertValidationFails('alphabetic_space',['field'=>'sometest123']);   
        $this->assertValidationFails('alphabetic_space',['field'=>'sometest 123']);   
        $this->assertValidationFails('alphabetic_space',['field'=>'sometest_hyphen']);

        $this->assertValidationSucceeds('alphabetic_space',['field'=>'a name with space']);

    } 

    public function testAlphNumericSpace()
    {
        $this->assertValidationSucceeds('alphanumeric_space',['field'=>'sometest123']);   
        $this->assertValidationSucceeds('alphanumeric_space',['field'=>'sometest 123']);   
        $this->assertValidationFails('alphanumeric_space',['field'=>'sometest_hyphen']);

        $this->assertValidationSucceeds('alphanumeric_space',['field'=>'a name with space']);

    }        

}