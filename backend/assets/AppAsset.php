<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css',
        'https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css', 
        // 'https://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css',
        'https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css',
        'app-assets/css/app.css',
        'app-assets/css/custom.css',
       
    ];
    public $js = [
        'app-assets/js/app.js',
        // 'app-assets/js/bootstrap.js',
        // 'app-assets/js/dropdown.js',
        // 'app-assets/js/chart.js',
        // 'app-assets/js/highlight.js',
        // 'app-assets/js/feather.js',
        // 'app-assets/js/slick.js',
        // 'app-assets/js/tooltipster.js',
        // 'app-assets/js/datatable.js',
        // 'app-assets/js/datepicker.js', // Date Range Picker
        
        // 'app-assets/js/select2.js',
        // 'app-assets/js/cropper.js',
        // 'app-assets/js/dropzone.js',
        
        // 'app-assets/js/summernote.js',
        'app-assets/js/jquery.validate.min.js',
        
        // 'app-assets/js/image-zoom.js',
        'app-assets/js/modal.js',
        // 'app-assets/js/svg-loader.js',
        // 'app-assets/js/toast.js',


        //
        'https://maps.googleapis.com/maps/api/js?key=AIzaSyCoOJ_2-o8EhcAbkdI5WnP9I5-wHbPuceU&libraries=places',
        'https://unpkg.com/sweetalert/dist/sweetalert.min.js',
        'https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js',
        // 'https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap.min.js',
        'app-assets/js/custom.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        // 'yii\bootstrap5\BootstrapAsset',
    ];
}