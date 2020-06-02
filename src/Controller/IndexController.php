<?php

// src/Controller/IndexController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class IndexController
{
    /**
     * @Route("/", name="root")
     */
    public function index()
    {
        $number = 1;

        return new Response(
            '<html><body>Lucky number: '.$number.'</body></html>'
        );

    }

    /**
     * @Route("/api/redsys", name="redsys")
     */
    public function redsys(Request $request)
    {

        // Get the POST parameters
        $parameters = json_decode($request->getContent(), true);

        // Get the product
        $productList = $this->db_getProductList();
        $product = $productList[$parameters['prod_id']];

        // Generate 12 digit random code. 4 digits from 0 to 9, 8 alphanumeric digits
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $aux['code'] = rand ( 1000 , 9999 ) .''. substr(str_shuffle($permitted_chars), 0, 8);

        // Generate Redsys data

        $data['Ds_Merchant_Amount'] = number_format($product['price'], 2, '', '');;
        $data['Ds_Merchant_Currency'] = 978;
        $data['Ds_Merchant_Order'] = $aux['code'];
        $data['Ds_Merchant_ProductDescription'] = $product['name'];
        $data['DS_Merchant_MerchantCode'] = '012809';
        $data['DS_Merchant_MerchantName'] = 'Circuito Pollos UMH';
        $data['DS_Merchant_Terminal'] = 50;
        $data['DS_Merchant_TransactionType'] = 0;
        $data['DS_Merchant_Titular'] = $parameters['nombre'].' '.$parameters['apellidos'];
        $data['DS_Merchant_urlOK'] = 'https://ce2020p4.customcarpet.es/thankyou';
        $data['DS_Merchant_urlKO'] = 'https://ce2020p4.customcarpet.es/cancel';
        $data['DS_Merchant_Signature'] = hash(
            "sha256",
            $data['Ds_Merchant_Amount'] . ''
            . $data['Ds_Merchant_Order']. ''
            . $data['DS_Merchant_MerchantCode']. ''
            . $data['Ds_Merchant_Currency']. ''
            . $data['DS_Merchant_TransactionType']. ''
            . $data['Ds_Merchant_Currency'] . 'UMH2809'
        );
        $data['DS_Merchant_Signature'] = strtoupper ($data['DS_Merchant_Signature']);

        $response = new JsonResponse($data);
        return $response;

    }

    /**
     * @Route("/api/products", name="get_products")
     */
    public function getProducts()
    {
        // Simulate we get the products from database
        $productList = $this->db_getProductList();

        $response = new JsonResponse($productList);
        return $response;

    }

    /**
     * @Route("/api/products/{id}", methods="GET", name="get_product_by_id")
     */
    public function getProductById(int $id)
    {
        // Simulate we get the products from database
        $productList = $this->db_getProductById($id);

        $response = new JsonResponse($productList);
        return $response;

    }

    public function db_getProductList(){

        $productList[0] = array(
            'id' => 0,
            'name'=>'Turismo',
            'price' => 25,
            'Description' => 'Disfrute del circuito',
            'img' => 'https://i.pinimg.com/originals/a5/fa/40/a5fa407cf5e948e124a58855a4c5ad0f.jpg'
        );
        $productList[1] = array(
            'id' => 1,
            'name'=>'Gran Turismo',
            'price' => 50,
            'Description' => 'Tómeselo en serio',
            'img' => 'https://images.unsplash.com/photo-1566274360936-69fae8dc1d95?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1050&q=80'
        );
        $productList[2] = array(
            'id' => 2,
            'name'=>'Superdeportivos',
            'price' => 288,
            'Description' => 'Lo importante es ganar',
            'img' => 'https://images.unsplash.com/photo-1529954223331-4be3b228703a?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1350&q=80'
        );

        return $productList;
    }

    public function db_getProductById($id){

        $productList = $this->db_getProductList();

        return $productList[$id];
    }


}