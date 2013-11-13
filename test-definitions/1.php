<?hh

class AutoloadedClass {
  public static function weNeedToGoDeeper() {
    printf("Class autoloaded!\n");
    autoloaded_function();
  }
}
