#!/bin/bash

#
# Mandatory graph configuration
#
GRAPHTYPE=svg
GRAPH=UNL_UCBCN.svg
CLASSES=.

TMPFILE=classgraph.dot

CLASS_PREFIX=UNL_UCBCN

#
# Optional graph configuration
#
CLASS_FONTSIZE=14
CLASS_FONTCOLOR=dodgerblue4
CLASS_BORDERCOLOR=dodgerblue1
CLASS_SHAPE=ellipse
CLASS_FONTNAME=Times-Roman

INTERFACE_FONTSIZE=14
INTERFACE_FONTCOLOR=darkorange3
INTERFACE_BORDERCOLOR=darkorange1
INTERFACE_SHAPE=box
INTERFACE_FONTNAME=Times-Roman

CLASS_INHERITANCE_COLOR=steelblue4
INTERFACE_INHERITANCE_COLOR=firebrick3
IMPLEMENTS_COLOR=tan2

RANK_DISTANCE=1.0


#
# Graph generation
#

rm $TMPFILE 2>/dev/null

# Check for svg output and correct some font bugs
if [ "$GRAPHTYPE" = "svg" ]; then
	ORIG_CLASS_FONTSIZE=$CLASS_FONTSIZE
	ORIG_INTERFACE_FONTSIZE=$INTERFACE_FONTSIZE
	let CLASS_FONTSIZE=$CLASS_FONTSIZE+10
	let INTERFACE_FONTSIZE=$INTERFACE_FONTSIZE+10
fi

# Start the graph
echo "digraph class_structure {" >>$TMPFILE

# Set the rank distance
echo "ranksep=$RANK_DISTANCE" >>$TMPFILE

# Set standard node look and feel
echo "node [fontsize=\"$CLASS_FONTSIZE\"]" >>$TMPFILE

# Create a configured node for every class
echo "Creating node for every class."
for i in `find $CLASSES -iname "*.php"`; do grep "class $CLASS_PREFIX" "$i" | sed -e "s@^.*class \\($CLASS_PREFIX[a-zA-Z0-9_]\\+\\).*\$@\\1 [fontsize=\"$CLASS_FONTSIZE\", fontcolor=\"$CLASS_FONTCOLOR\", color=\"$CLASS_BORDERCOLOR\", shape=\"$CLASS_SHAPE\", fontname=\"$CLASS_FONTNAME\"]@" >>$TMPFILE; done

# Create a configured node for the "Exception" class
echo "Exception [fontsize="$CLASS_FONTSIZE", fontcolor=\"$CLASS_FONTCOLOR\", color=\"$CLASS_BORDERCOLOR\", shape=\"$CLASS_SHAPE\", fontname=\"$CLASS_FONTNAME\"]" >>$TMPFILE

# Create a configured node for every interface
echo "Creating node for every interface."
for i in `find $CLASSES -iname "*.php"`; do grep "interface $CLASS_PREFIX" "$i" | sed -e "s@^.*interface \\($CLASS_PREFIX[a-zA-Z0-9_]\\+\\).*\$@\1 [fontsize="$INTERFACE_FONTSIZE", fontcolor=\"$INTERFACE_FONTCOLOR\", color=\"$INTERFACE_BORDERCOLOR\", shape=\"$INTERFACE_SHAPE\", fontname=\"$INTERFACE_FONTNAME\"]@" >>$TMPFILE; done

# Create edges for class inheritance
echo "Creating class inheritance edges."
for i in `find $CLASSES -iname "*.php"`; do grep "class $CLASS_PREFIX" "$i"|grep "extends"|sed -e "s@^.*class \\($CLASS_PREFIX[a-zA-Z0-9_]\\+\\).*extends \\([a-zA-Z0-9_]\\+\\).*\$@\\2->\\1 [dir=back, color=\"$CLASS_INHERITANCE_COLOR\"]@" >>$TMPFILE; done

# Create edges for interface inheritance
echo "Creating interface inheritance edges."
for i in `find $CLASSES -iname "*.php"`; do grep "interface $CLASS_PREFIX" "$i"|grep "extends"|sed -e "s@^.*interface \\($CLASS_PREFIX[a-zA-Z0-9_]\\+\\).*extends \\([a-zA-Z0-9_]\\+\\).*\$@\\2->\\1 [dir=back, color=\"$INTERFACE_INHERITANCE_COLOR\"]@" >>$TMPFILE; done


# Create edges for interface implementation
echo "Creating interface implementation edges."
for i in `find $CLASSES -iname "*.php"`; do grep "class $CLASS_PREFIX" "$i"|grep "implements"|sed -e "s@^.*class \\($CLASS_PREFIX[a-zA-Z0-9_]\\+\\).*implements \\([a-zA-Z0-9_, ]\\+\\)@{\\2}->\\1 [dir=back, color=\"$IMPLEMENTS_COLOR\"]@"|sed -e 's@ @@g'|sed -e 's@,@ @g' >>$TMPFILE; done

# Close the graph
echo "}" >>$TMPFILE

echo "Generating graph."
dot $TMPFILE -T$GRAPHTYPE -o $GRAPH

# Correct the fonts if svg output driver is used
if [ "$GRAPHTYPE" = "svg" ]; then
	mv $GRAPH $TMPFILE
	cat $TMPFILE|sed -e "s@font-size:$CLASS_FONTSIZE.00pt@font-size:$ORIG_CLASS_FONTSIZE.00pt@"|sed -e "s@font-size:$INTERFACE_FONTSIZE.00pt@font-size:$ORIG_INTERFACE_FONTSIZE.00pt@" >$GRAPH
fi

echo "Deleting temporary files."
rm $TMPFILE

echo "All done."
