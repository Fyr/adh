<?php
App::uses('AppHelper', 'View/Helper');
class ObjectTypeHelper extends AppHelper {
    public $helpers = array('Html');
    
    private function _getTitles() {
        $Titles = array(
            'index' => array(
                'Article' => __('Articles'),
                'Page' => __('Static pages'),
                'News' => __('News'),
                'Faq' => __('FAQ'),
                'PageBlock' => __('Static page blocks'),
                'Category' => __('Categories'),
                'CategoryBlock' => __('Category blocks'),
                'Product' => __('Products'),
                'ProductBlock' => __('Product blocks'),
                'ParamGroup' => __('Tech.param groups'),
                'PMFormField' => __('Tech.params'),
                'SongPack' => __('Song PDF-packs'),
                'Song' => __('Songs'),
                'Service' => __('Personal order services'),
                'SubscrPlan' => __('Subscription plans'),
                'ProductPack' => __('Product packs'),
                'PackDiscount' => __('Song pack discounts'),
                'User' => __('User profiles'),
                'Order' => __('Orders'),
            ),
            'create' => array(
                'Article' => __('Create article'),
                'Page' => __('Create static page'),
                'News' => __('Create news article'),
                'Faq' => __('Create FAQ section'),
                'PageBlock' => __('Create static page block'),
                'Category' => __('Create category'),
                'CategoryBlock' => __('Create category block'),
                'Product' => __('Create product'),
                'ProductBlock' => __('Create product block'),
                'ParamGroup' => __('Create tech.param group'),
                'PMFormField' => __('Create tech.param'),
                'SongPack' => __('Create PDF-pack'),
                'Song' => __('Create song'),
                'PackDiscount' => __('Create discount'),
                'Service' => __('Create service'),
                'SubscrPlan' => __('Create subscription plan'),
                'ProductPack' => __('Create product pack'),
                'User' => __('Create user'),
                'Order' => __('Create order'),
            ),
            'edit' => array(
                'Article' => __('Edit article'),
                'Page' => __('Edit static page'),
                'News' => __('Edit news article'),
                'Faq' => __('Edit FAQ section'),
                'PageBlock' => __('Edit static page block'),
                'Category' => __('Edit category'),
                'CategoryBlock' => __('Edit category block'),
                'Product' => __('Edit product'),
                'ProductBlock' => __('Edit product block'),
                'ParamGroup' => __('Edit tech.param group'),
                'PMFormField' => __('Edit tech.param'),
                'SongPack' => __('Edit PDF-pack'),
                'Song' => __('Edit song'),
                'PackDiscount' => __('Edit discount'),
                'Service' => __('Edit service'),
                'SubscrPlan' => __('Edit subscription plan'),
                'ProductPack' => __('Edit product pack'),
                'User' => __('Edit user'),
                'Order' => __('View order'),
            ),
            'view' => array(
            	'Article' => __('View article'),
            	'News' => __('View news article'),
            	'Product' => __('View product'),
                'Order' => __('View order'),
            )
        );
        return $Titles;
    }
    
    public function getTitle($action, $objectType) {
        $aTitles = $this->_getTitles();
        return (isset($aTitles[$action][$objectType])) ? $aTitles[$action][$objectType] : $aTitles[$action]['Article'];
    }
    
    public function getBaseURL($objectType, $objectID = '') {
        return $this->Html->url(array('action' => 'index', $objectType, $objectID));
    }
}