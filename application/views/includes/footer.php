</body><!--opened in header.php-->
<?
    $controller_main_page = $this->router->fetch_class(); //currently served class
    $controller_method_page = $this->router->fetch_method(); //currently served method 
?>

<script src="<?= base_url('assets/js/jquery-1.12.3.min.js')?>"></script>

<?/** we dont need datatables on the authentication pae */if($controller_main_page != "auth"):?>
    <script src="<?= base_url('assets/js/datatables/media/js/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?= base_url('assets/js/datatables/custom/dataTables.customAPI.js'); ?>"></script>
<?endif;?>

<?
/**load script files if the filepath shares the calling class and method**/
if(file_exists(BASEPATH . '../www/assets/js/' . $controller_main_page . '/' . $controller_method_page . '.js')):?>
    <script src="<?php echo base_url('assets/js/' . $controller_main_page . '/' . $controller_method_page . '.js'); ?>?v=1.0"></script>
<?endif;?>


</html><!--opened in header.php-->

