$(document).ready(function()
{
    $('#datatable-item-sales').dataTable(
        {
            "bJQueryUI": true,
            "sDom": 'T<"clear">lfrtip',
            "oTableTools":
            {
                "sSwfPath": "/admin/assets/media/swf/copy_csv_xls_pdf.swf"
            },
            "bProcessing": true,
            "bServerSide": true,
            "sServerMethod": "POST",
            "sAjaxSource": "/welcome/ajax_get_all_users",
            "iDisplayLength": 2,
            "aLengthMenu": [[2, 4, 10], [2, 4, 10]],
            "aaSorting": [[2, 'desc']],
            "aoColumns": [
                { "bVisible": true, "bSearchable": true, "bSortable": true },
                { "bVisible": true, "bSearchable": true, "bSortable": true },
                { "bVisible": true, "bSearchable": true, "bSortable": true },
                { "bVisible": true, "bSearchable": true, "bSortable": true },
                { "bVisible": true, "bSearchable": true, "bSortable": true }
            ]
        }).fnSetFilteringDelay(700);
    
});