<?php

// src/Controller/IndexController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;


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
    public function redsys()
    {

        $response = new JsonResponse(1);
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
            'name'=>'Normal suscription',
            'price' => 288,
            'image_url' => '...'
        );
        $productList[1] = array(
            'name'=>'Premium suscription',
            'price' => 288,
            'image_url' => '...'
        );

        return $productList;
    }

    public function db_getProductById($id){

        $productList = $this->db_getProductList();

        return $productList[$id];
    }


}