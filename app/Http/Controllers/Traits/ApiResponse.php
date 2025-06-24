<?php

namespace App\Http\Controllers\Traits;

use App;
use App\Exceptions\FrontException;
use App\Models\User;
use Closure;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

trait ApiResponse
{

    public function apiResponse($code, $data = [], $messages = null, Throwable $e = null): JsonResponse
    {
        return response()->json([
            'code' => $code,
            'data' => $data,
            'messages' => $messages,
        ]);
    }

    public function tryInRestrictedContext(Request $request, Closure $callBack, $transactional = true)
    {
        return $this->throwExceptionRequestContext($request, $callBack, $transactional);
    }

    public function tryInUnrestrictedContext(Request $request, Closure $callBack, $transactional = true)
    {
        return $this->throwExceptionRequestContext($request, $callBack, $transactional, false);
    }

    public function throwExceptionRequestContext(Request $request, Closure $callBack, $transactional = true, $exceptionOnUserAbsence = true)
    {
        return $this->throwExceptionContext(function () use ($request, $callBack, $exceptionOnUserAbsence) {
            $user = $request->user();
            if (!($user instanceof User) && $exceptionOnUserAbsence) {
                throw new Exception('User not found');
            }

            return $callBack($request, $user);
        }, $transactional);
    }

    public function throwExceptionContext(Closure $callBack, $transactional = true)
    {
        try {
            if ($transactional) DB::beginTransaction();

            $response = $callBack();

            if ($transactional) DB::commit();

            return $response;
        } catch (FrontException $e) {
            if ($transactional) DB::rollBack();
            return $this->apiResponse(500, [], $e->getMessage());
        } catch (Exception $e) {
            if ($transactional) DB::rollBack();

            $message = $e->getMessage();
            $data = $e->getTrace();
            if (App::environment('production')) {
                $message = 'Server Failure';
                $data = [];
            }

            return $this->apiResponse(500, $data, $message, $e);
        }
    }

    public function paginatedApiResponse(Request $request, $code, Builder $data, $messages = '', ?callable $callable = null, $ressource = null)
    {
        $show = intval($request->query('show'));
        $pagination = $data->paginate($show > 0 ? $show : config('app.app_pagination_max_value'));
        if ($callable) $pagination = $callable($pagination, $request);
        if ($ressource) $pagination = new $ressource($pagination);

        return response()->json([
            'code' => $code,
            'data' => $pagination,
            'messages' => $messages,
        ]);
    }
}
