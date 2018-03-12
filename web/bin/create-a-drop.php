<?php

/**
 * @file
 * Create backdrop-playground apps.
 */

print_r($_POST['app-name']);

$appName = rtrim($_POST['app-name']);
$appName = htmlspecialchars($appName);

print $appName;

// Make a directory for our app.
passthru(
  "mkdir -p ../../drops/$appName"
);

$f = fopen('../../drops/' . $appName . '/.lando.yml', 'x+');
  fwrite($f, "name: $appName\n");
  fwrite($f, "recipe: backdrop\n");
fclose($f);

// Get backdrop code base.
passthru(
  "cd ../../drops/$appName && wget https://github.com/backdrop/backdrop/archive/1.9.2.tar.gz"
);


print_r(getcwd());
// Untar the backdrop.
passthru(
  "cd ../../drops/$appName && tar xzf 1.9.2.tar.gz"
);

// Move .lando.yml into the new app.
passthru(
  "cd ../../drops/$appName && mv .lando.yml backdrop-1.9.2"
);

// Start the lando app.
passthru(
  "cd ../../drops/$appName && lando start"
);

sleep(14);

// Check if there is a site to use.
$handle = curl_init("http://$appName.lndo.site");
curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);

/* Get the HTML or whatever is linked in $url. */
$response = curl_exec($handle);

/* Check for 404 (file not found). */
$httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
if($httpCode == 200) {
  print "<p>Visit your site now: <a href=\"http://$appName.lndo.site\">http://$appName.lndo.site</a></p>";
} else {
  print "<p>No site ;(.</p>";
}

curl_close($handle);

// @todo: Install the site.
//
// @todo: Download the modules, themes, and layouts.
//
// @todo: Enable the modules, themes, and layouts.


