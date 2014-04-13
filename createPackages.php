<?php
$debs = "debs/";
$tempdir = "/tmp/pkgcreator-cache";
$control_path = $tempdir . "/control";
$output = "";
if(!is_dir($tempdir))
{
mkdir($tempdir);
}
foreach(glob("{$debs}*.deb") as $deb)
{
shell_exec("dpkg-deb --control {$deb} {$tempdir}");
if(!file_exists($control_path))
{
die("ERROR: couldn't run dpkg-deb check if 1) /tmp exists 2) dpkg-deb is installed on your system");
}
$control = file_get_contents($control_path);
$control .= "Filename: ./" . $deb;
$control .= "\nMD5sum: " . md5_file($deb);
$control .= "\nSHA1: " . sha1_file($deb);
$control .= "\nSHA256: " . hash('sha256', file_get_contents($deb));
$control .= "\nSize: " . filesize($deb);
$output .= $control . "\n\n\n";
}
shell_exec("rm -rf {$tempdir}");
//file_put_contents("Packages",$output);
file_put_contents("Packages.bz2",bzcompress($output));
?>
