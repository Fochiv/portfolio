{pkgs}: {
  deps = [
    pkgs.php83Extensions.sqlite3
    pkgs.php83Extensions.pdo_sqlite
    pkgs.php83
  ];
}
