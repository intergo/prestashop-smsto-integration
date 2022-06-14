cd ../
rm smsto.zip
zip -r smsto.zip smsto -x "smsto/.git/*" "build.sh"
mv smsto.zip ./smsto