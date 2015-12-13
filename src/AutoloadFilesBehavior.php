<?hh // strict

namespace FredEmmott\AutoloadMap;

enum AutoloadFilesBehavior: string {
  FIND_DEFINITIONS = 'scan';
  EXEC_FILES = 'exec';
}
