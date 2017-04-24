<?php

use PHPUnit\Framework\TestCase;

use FormGuide\PHPFormValidator\FormValidator;

class FormValidatorTest extends TestCase
{

    public function testValidationsSimpleMethod()
    {
        $validator = FormValidator::create();

        $validator->field('name')
        ->isRequired()
        ->maxLength(50);
        $validator->field('email')
        ->isEmail()->isRequired()
        ->maxLength(50);

        $validator->field('message')->isRequired()->maxLength(2048);

        //$validator->fields(['name','email'])->maxLength(50);
        $post=[];

        $validator->test($post);

        $this->assertTrue($validator->hasErrors());

    }

    public function testErrorMessages()
    {
        $validator = FormValidator::create();

        $validator->field('name')->isRequired();
        $validator->field('email')->isEmail();

        $validator->test(['email'=>'plaintext']);

        $this->assertTrue($validator->hasErrors());

        $errors = $validator->getErrors();

        $this->assertEquals(2,count($errors));

        $asso_errors = $validator->getErrors(/*associative*/ true);

        $this->assertFalse( empty($asso_errors['name']) );
        $this->assertFalse( empty($asso_errors['email']) );
    }

    public function testMultipleFields()
    {
        $validator = FormValidator::create();
        $validator->fields(['name','email'])->areRequired()->maxLength(20);
        $validator->field('email')->isEmail();

        $post=['email'=>'somelong123456789012345678901234567890@domain.cc'];

        $validator->test($post);

        $this->assertTrue($validator->hasErrors());

        $asso_errors = $validator->getErrors(/*associative*/ true);

        $this->assertFalse( empty($asso_errors['name']) );
        $this->assertFalse( empty($asso_errors['email']) );
    }

    public function testMultipleFields2()
    {
        $validator = FormValidator::create();
        $validator->fields(['email1','email2'])->areRequired()->areEmails()->maxLength(20);

        $post=['email1'=>'someone@domain.cc'];

        $validator->test($post);

        $this->assertTrue($validator->hasErrors());

        $asso_errors = $validator->getErrors(/*associative*/ true);

        $this->assertTrue( empty($asso_errors['email1']) );
        $this->assertFalse( empty($asso_errors['email2']) );
    }

    public function testMultipleAlphabetic()
    {
        $validator = FormValidator::create();
        $validator->fields(['field1','field2'])->areRequired()->areAlphabetic();

        $post=['field1'=>'someone@domain.cc','field2'=>'cleanname'];

        $validator->test($post);

        $this->assertTrue($validator->hasErrors());

        $asso_errors = $validator->getErrors(/*associative*/ true);

        $this->assertFalse( empty($asso_errors['field1']) );
        $this->assertTrue( empty($asso_errors['field2']) );        
    }

}

