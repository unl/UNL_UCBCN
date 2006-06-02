php makepackage.php make
rm UNL_UCBCN-*.tgz
pear package
pear install -f UNL_UCBCN-0.0.2.tgz
pear run-scripts unl/UNL_UCBCN

