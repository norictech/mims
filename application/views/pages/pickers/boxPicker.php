<div class="table-responsive">
    <button type="button" data-fancybox-close class="btn btn-danger float-right ml-2"  style="float:right">CLOSE</button>
    <button id="submitButton" class="btn btn-info float-right">PILIH</button>
    <h3><b>Pilih Container/Box</b></h3>
    <hr>
    <table id="picker" class="table table-striped table-bordered border display" style="width:<?=(array_sum($width) < 100 ? '100' : array_sum($width))?>%;z-index:99999 !important;">
        <thead>
            <tr>
                <th></th>
                <?php if ($totalUnusedColumn == '3') echo "<th></th>"; ?>
                <th>NO</th>
                <?php
                    foreach ($columns as $columnKey => $columnAliasing) {
                        echo "<th>".$columnAliasing."</th>";
                    }
                ?>
            </tr>
        </thead>
    </table>
</div>

<script>
    $(document).ready(function() {
        $('.fancybox-close-small').css('display', 'none');

        table = $('#picker').DataTable({
            "sDom": "Rlfrtip",
            "scrollX": true,
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url" : "<?=site_url(uriSegment(1).'/'.uriSegment(2).'?mode=data')?>",
                'method': 'POST',
                'data': function(d) {
                    return $.extend(d, filters)
                },
            },
            "deferRender": true,
            "columns": [
                {},
                <?php if ($totalUnusedColumn == '3') { ?>
                    {
                        "className": 'details-control',
                        "data": null,
                        "orderable": false,
                        "defaultContent": ''
                    },
                <?php } ?>
                { 
                    "className": 'datatables-number',
                    "data": null,
                    "orderable": false,
                    "defaultContent": '',
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                <?php 
                    $key = 0;
                    foreach ($columns as $columnKey => $columnAliasing) { 
                ?>
                    {
                        "className": '<?=$key == 0 ? 'idValue' : '' ?>',
                        "data": <?=$key?>,
                    },
                <?php $key++; } ?>
            ],
            "columnDefs": [
                <?php foreach ($width as $key => $value) { ?>
                    {
                        "width": <?=$value?>+'%',
                        "targets": <?=$totalUnusedColumn+$key?>
                    },
                <?php } ?>
                {
                    "width": "5%", 
                    "targets": 0,
                    "checkboxes": {
                        "selectRow": true
                    },
                },
                {
                    "width": "5%", 
                    "targets": 1,
                },
                <?php if ($totalUnusedColumn == '3') { ?>
                    {
                        "width": "5%", 
                        "targets": 2
                    },
                <?php } ?>
                {
                    "visible": false,
                    "targets": <?=$totalUnusedColumn?>,
                },
                <?php 
                $key = 0;
                foreach ($columns as $colKey => $colValue) { ?>
                    {
                        "render": function(data, type, row) {
                            <?php if ($colKey == 'assetParent') { ?>
                                var kode = 'MIC';
                                return '<a href="#" onClick="detailButton('+row[0]+')">'+kode+'-'+(kode == 'MIP' ? (row[1] ? row[1]+'-' : '') : '')+row[0]+'</a>';
                            <?php } else if ($colKey == 'propAdmin.priceBuy') { ?>
                                return (row[<?=$key?>] ? "Rp " + formatRupiah(row[<?=$key?>].toString()) : '-');
                            <?php } else if ($colKey == 'propAdmin.procureDate') { ?>
                                return (row[<?=$key?>] ? moment(row[<?=$key?>]).locale('id').format('dddd, DD/MM/Y') : '-');
                            <?php } else { ?>
                                return row[<?=$key?>] ? row[<?=$key?>] : '-';
                            <?php } ?>
                        },
                        "targets": <?=$totalUnusedColumn+$key?>,
                    },
                <?php $key++; } ?>
            ],
            'select': {
                'style': 'single'
            },
            "order": [[3, 'asc']],
            "oLanguage": {
                "sSearch": "Quick Search",
                "sProcessing": '<image style="width:150px" src="http://superstorefinder.net/support/wp-content/uploads/2018/01/blue_loading.gif">',
            }
        });
    });

    $('#submitButton').click(function() {
        var rows_selected = table.column(0).checkboxes.selected();
        
        parent.setBox(rows_selected);
        $.fancybox.close();
    });
</script>
