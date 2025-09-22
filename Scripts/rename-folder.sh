cd /Users/lucasprabant/Library/Mobile Documents/com~apple~CloudDocs
folders=`ls`
for i in $array; do
    if [[ $i =~ [0-9]{2} ]]; then echo "$i"; else echo moop;fi
done


if "$i" -~ [a-zA-Z0-9\ ]; then BLAH; fi

einfolder="01 - Projects"
ptrn=
if "$einfolder" -~ [0-9]{2}; then echo meep; fi

#if "$i" -~ [0-9]{2}; then echo meep; fi

shopt -s nullglob
array=(ls)
shopt -u nullglob # Turn off nullglob to make sure it doesn't interfere with anything later
echo "${array[@]}"  