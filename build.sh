cd ../
rm smsto.zip
zip -r smsto.zip smsto -x "smsto/.git/*" "smsto/build.sh" "smsto/docker/*"
mv smsto.zip ./smsto