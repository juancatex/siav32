<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler; 
class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)

    {    if ($exception instanceof AuthenticationException) {
        return redirect('/');
    }
    if ($exception instanceof TokenMismatchException) {

        return redirect(route('/'))->with('status', 'You page session expired. Please try again');
    }
        if (!$request->ajax()) {
            if(strcasecmp($exception->getCode(), '2002')==0){
                return redirect('/')->with('error',[$exception->getCode(),utf8_encode($exception->getMessage())]);
              }else{ 
                return parent::render($request, $exception); 
              }
        }else{
            return parent::render($request, $exception); 
            // $valorrequest=parent::render($request, $exception); 
            // return  response()->json(['mensaje'=>$valorrequest],$valorrequest->status());
        }
        
           
        //return parent::render($request, $exception);    
        
        /*if( $exception instanceof TokenMismatchException){
            return response()
                ->view('errors.401', ['error' => 'Page expired, go back and try again'], 401);
        }

        return parent::render($request, $exception); */
    }


    
}
