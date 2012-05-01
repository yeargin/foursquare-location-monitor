#!/bin/bash
E_BADARGS=65

if [ ! -n "$1" ]
then
  echo "Usage: `basename $0` ./path/to/script.sh timeToSleep"
  exit $E_BADARGS
fi  


while [[ 1=1 ]]; do
	$1
	sleep $2
done