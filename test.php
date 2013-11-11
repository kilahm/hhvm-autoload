<?php

define('EXAMPLE_CONSTANT_1', 'herp');
const EXAMPLE_CONSTANT_2 = 'derp';
function example_function() {
}
class ExampleClass {
  /* Should not be in function map */
  const EXAMPLE_MEMBER_CONSTANT = 'how do I shot web?';
  function exampleMemberFunction() {
  }
}

var_export(
  fe_xhpast2_definitions(__FILE__, FE_XHPAST2::ALLOW_HIPHOP_SYNTAX)
);
