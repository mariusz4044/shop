<?php

namespace App\Products\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Products\QueryHandler\GetProductCollectionHandler;
use Symfony\Component\HttpFoundation\Request;

class GetProductCollectionAction
{
    private GetProductCollectionHandler $handler;

    public function __construct(GetProductCollectionHandler $handler)
    {
        $this->handler = $handler;
    }
    
    public function __invoke(Request $request):JsonResponse
    {
        $productCollection = $this->handler->handle($request);
        if(is_null($productCollection)){
            return new JsonResponse([], Response::HTTP_NOT_FOUND);
        } else{
            return new JsonResponse($productCollection, Response::HTTP_OK);
        }    
    }
}