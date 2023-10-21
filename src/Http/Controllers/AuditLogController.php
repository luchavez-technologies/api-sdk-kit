<?php

namespace Luchavez\ApiSdkKit\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
// Model
use Luchavez\ApiSdkKit\Events\AuditLog\AuditLogArchivedEvent;
// Requests
use Luchavez\ApiSdkKit\Events\AuditLog\AuditLogCollectedEvent;
use Luchavez\ApiSdkKit\Events\AuditLog\AuditLogCreatedEvent;
use Luchavez\ApiSdkKit\Events\AuditLog\AuditLogRestoredEvent;
use Luchavez\ApiSdkKit\Events\AuditLog\AuditLogShownEvent;
use Luchavez\ApiSdkKit\Events\AuditLog\AuditLogUpdatedEvent;
use Luchavez\ApiSdkKit\Http\Requests\AuditLog\DeleteAuditLogRequest;
// Events
use Luchavez\ApiSdkKit\Http\Requests\AuditLog\IndexAuditLogRequest;
use Luchavez\ApiSdkKit\Http\Requests\AuditLog\RestoreAuditLogRequest;
use Luchavez\ApiSdkKit\Http\Requests\AuditLog\ShowAuditLogRequest;
use Luchavez\ApiSdkKit\Http\Requests\AuditLog\StoreAuditLogRequest;
use Luchavez\ApiSdkKit\Http\Requests\AuditLog\UpdateAuditLogRequest;
use Luchavez\ApiSdkKit\Models\AuditLog;

/**
 * Class AuditLogController
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class AuditLogController extends Controller
{
    /**
     * AuditLog List
     *
     * @group AuditLog Management
     *
     * @param  IndexAuditLogRequest  $request
     * @return JsonResponse
     */
    public function index(IndexAuditLogRequest $request): JsonResponse
    {
        $data = new AuditLog();

        if ($request->has('full_data') === true) {
            $data = $data->get();
        } else {
            $data = $data->simplePaginate($request->get('per_page', 15));
        }

        event(new AuditLogCollectedEvent($data));

        return simpleResponse()
            ->data($data)
            ->message('Successfully collected record.')
            ->success()
            ->generate();
    }

    /**
     * Create AuditLog
     *
     * @group AuditLog Management
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $data = $request->all();

        $model = AuditLog::create($data)->fresh();

        event(new AuditLogCreatedEvent($model));

        return simpleResponse()
            ->data($model)
            ->message('Successfully created record.')
            ->success()
            ->generate();
    }

    /**
     * Store AuditLog
     *
     * @group AuditLog Management
     *
     * @param  StoreAuditLogRequest  $request
     * @return JsonResponse
     */
    public function store(StoreAuditLogRequest $request): JsonResponse
    {
        $data = $request->all();

        $model = AuditLog::create($data)->fresh();

        event(new AuditLogCreatedEvent($model));

        return simpleResponse()
            ->data($model)
            ->message('Successfully created record.')
            ->success()
            ->generate();
    }

    /**
     * Show AuditLog
     *
     * @group AuditLog Management
     *
     * @param  ShowAuditLogRequest  $request
     * @param  AuditLog  $auditLog
     * @return JsonResponse
     */
    public function show(ShowAuditLogRequest $request, AuditLog $auditLog): JsonResponse
    {
        event(new AuditLogShownEvent($auditLog));

        return simpleResponse()
            ->data($auditLog)
            ->message('Successfully collected record.')
            ->success()
            ->generate();
    }

    /**
     * Edit AuditLog
     *
     * @group AuditLog Management
     *
     * @param  Request  $request
     * @param  AuditLog  $auditLog
     * @return JsonResponse
     */
    public function edit(Request $request, AuditLog $auditLog): JsonResponse
    {
        // Logic here...

        event(new AuditLogUpdatedEvent($auditLog));

        return simpleResponse()
            ->data($auditLog)
            ->message('Successfully updated record.')
            ->success()
            ->generate();
    }

    /**
     * Update AuditLog
     *
     * @group AuditLog Management
     *
     * @param  UpdateAuditLogRequest  $request
     * @param  AuditLog  $auditLog
     * @return JsonResponse
     */
    public function update(UpdateAuditLogRequest $request, AuditLog $auditLog): JsonResponse
    {
        // Logic here...

        event(new AuditLogUpdatedEvent($auditLog));

        return simpleResponse()
            ->data($auditLog)
            ->message('Successfully updated record.')
            ->success()
            ->generate();
    }

    /**
     * Archive AuditLog
     *
     * @group AuditLog Management
     *
     * @param  DeleteAuditLogRequest  $request
     * @param  AuditLog  $auditLog
     * @return JsonResponse
     */
    public function destroy(DeleteAuditLogRequest $request, AuditLog $auditLog): JsonResponse
    {
        $auditLog->delete();

        event(new AuditLogArchivedEvent($auditLog));

        return simpleResponse()
            ->data($auditLog)
            ->message('Successfully archived record.')
            ->success()
            ->generate();
    }

    /**
     * Restore AuditLog
     *
     * @group AuditLog Management
     *
     * @param  RestoreAuditLogRequest  $request
     * @param    $auditLog
     * @return JsonResponse
     */
    public function restore(RestoreAuditLogRequest $request, $auditLog): JsonResponse
    {
        $data = AuditLog::withTrashed()->find($auditLog)->restore();

        event(new AuditLogRestoredEvent($data));

        return simpleResponse()
            ->data($data)
            ->message('Successfully restored record.')
            ->success()
            ->generate();
    }
}
