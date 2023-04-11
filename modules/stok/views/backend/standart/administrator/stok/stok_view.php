<table class="table table-bordered table-striped dataTable">
	<thead>
		<tr class="">
			<th>No.</th>
			<th>Kategori Barang</th>
			<th>Nama Barang</th>
			<th>Jumlah Barang</th>
		</tr>
	</thead>
	<tbody id="tbody_stok">
<?php
	$no = 1;
	foreach ($data as $item) {
?>
		<tr>
			<td><?= $no++;?></td>
			<td><?= $item['kategori_barang'];?></td>
			<td><?= $item['nama_barang'];?></td>
			<td><?= $item['jumlah_barang'];?></td>
		</tr>
<?php
	}

	if (count($data) == 0) {
?>
	<tr>
		<td colspan="10">
			Mutasi Masuk data is not available
		</td>
	</tr>
<?php
	}
?>
	</tbody>
</table>