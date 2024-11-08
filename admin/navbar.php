<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="#">JV Samelan</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">
      <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'entire-list.php') ? 'active' : ''; ?>">
        <a class="nav-link" href="./entire-list.php">Entire List</a>
      </li>
      <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'present.php') ? 'active' : ''; ?>">
        <a class="nav-link" href="./present.php">Present List</a>
      </li>
      <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'absent.php') ? 'active' : ''; ?>">
        <a class="nav-link" href="./absent.php">Absent List</a>
      </li>
      <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'pending.php') ? 'active' : ''; ?>">
        <a class="nav-link" href="./pending.php">Pending List</a>
      </li>
      <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'analysis.php') ? 'active' : ''; ?>">
        <a class="nav-link" href="./analysis.php">Analysis Report</a>
      </li>
      <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'accomodation.php') ? 'active' : ''; ?>">
        <a class="nav-link" href="./accomodation.php">Accommodation Report</a>
      </li>
      <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'export.class.php') ? 'active' : ''; ?>">
        <a class="nav-link" href="./export.class.php">Download Report</a>
      </li>
      <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'logout.php') ? 'active' : ''; ?>">
        <a class="nav-link" href="./logout.php">Logout</a>
      </li>
    </ul>
  </div>
</nav>
