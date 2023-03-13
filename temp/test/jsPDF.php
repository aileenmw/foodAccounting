<?php
?>
<table id="my-table" data="thisData">
  <thead><tr><th>id</th><th>id1</th><th>id2</th></tr></thead>
  <tbody><tr><td>1</td><td>2</td><td>3</td></tbody>
</table>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js" integrity="sha512-qZvrmS2ekKPF2mSznTQsxqPgnpkI4DNTlrdUmTzrDgektczlKNRRhy5X5AAOnx5S09ydFYWWNSfcEqDTTHgtNA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="jspdf.min.js"></script>
<script src="jspdf.plugin.autotable.min.js"></script>
<script>
  var doc = new jsPDF()
  doc.autoTable({ html: '#my-table' })
  doc.save('table.pdf')
</script>