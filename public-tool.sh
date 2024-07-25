#!/usr/bin/bash

TOOL_PATH="<TOOL-PATH>"



source /usr/bin/pxn/scripts/common.sh  || exit 1
if [[ -z $TOOL_PATH                     ]] \
|| [[ "$TOOL_PATH" == "<""TOOL-PATH"">" ]]; then
	TOOL_PATH="$WDIR"
fi
if [[ ! -z  $TOOL_PATH                   ]] \
&& [[   -e "$TOOL_PATH/public/index.php" ]]; then
	TOOL_PATH="$TOOL_PATH/public"
fi
if [[ -z $TOOL_PATH ]]; then
	echo
	failure "index.php not found for this tool"
	failure ; exit 1
fi
if [[ ! -e "$TOOL_PATH" ]]; then
	echo
	failure "index.php not found for this tool at path: $TOOL_PATH"
	failure ; exit 1
fi
php  "$TOOL_PATH/index.php"
exit $?
