#!/bin/sh

DIRNAME=$(dirname $0)
REALPATH=$(which realpath)
if [ ! -z "${REALPATH}" ]; then
  DIRNAME=$(realpath ${DIRNAME})
fi

${HPHP_HOME}/hphp/hhvm/hhvm \
  -vDynamicExtensions.0=${DIRNAME}/fe_autoload_map_generator.so \
  ${DIRNAME}/test.php
