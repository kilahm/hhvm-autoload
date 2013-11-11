<?hh

class AutoloadedClass {
  static function weNeedToGoDeeper() {
    printf("Class autoloaded!\n");
    autoloaded_function();
  }
}
