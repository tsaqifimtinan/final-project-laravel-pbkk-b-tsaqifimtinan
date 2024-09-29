<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SwaggerController extends Controller
{
    public function show()
    {
        $documentation = 'default'; // or the appropriate value
        $urlToDocs = url('api-docs/api-docs.json'); // Define the URL to your Swagger docs
        $operationsSorter = 'alpha'; // Example value
        $configUrl = null; // Example value
        $validatorUrl = null; // Example value
        $useAbsolutePath = true; // Example value

        return view('vendor.l5-swagger.index', compact('documentation', 'urlToDocs', 'operationsSorter', 'configUrl', 'validatorUrl', 'useAbsolutePath'));
    }
}