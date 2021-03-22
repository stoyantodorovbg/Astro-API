<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redis;
use App\Http\Requests\GetDataRequest;
use App\Services\Interfaces\FormatDataServiceInterface;
use App\Services\Interfaces\ValidationServiceInterface;
use App\Services\Interfaces\HttpConnectorServiceInterface;
use App\Services\Interfaces\GenerateCommandServiceInterface;
use App\Repositories\Interfaces\HeliacalEventRepositoryInterface;

class DataController extends Controller
{
    /**
     * @var HttpConnectorServiceInterface
     */
    protected HttpConnectorServiceInterface $httpConnectorService;

    /**
     * @var GenerateCommandServiceInterface
     */
    protected GenerateCommandServiceInterface $generateCommandService;

    /**
     * @var ValidationServiceInterface
     */
    protected ValidationServiceInterface $validationService;

    /**
     * @var int
     */
    protected int $status = 200;

    /**
     * @var FormatDataServiceInterface
     */
    protected FormatDataServiceInterface $formatDataService;

    /**
     * @var HeliacalEventRepositoryInterface
     */
    protected $heliacalEventRepository;

    /**
     * DataController constructor.
     *
     * @param HttpConnectorServiceInterface $httpConnectorService
     * @param GenerateCommandServiceInterface $generateCommandService
     * @param ValidationServiceInterface $validationService
     * @param FormatDataServiceInterface $formatDataService
     * @param HeliacalEventRepositoryInterface $heliacalEventRepository
     */
    public function __construct(HttpConnectorServiceInterface $httpConnectorService,
                                GenerateCommandServiceInterface $generateCommandService,
                                ValidationServiceInterface $validationService,
                                FormatDataServiceInterface $formatDataService,
                                HeliacalEventRepositoryInterface $heliacalEventRepository
    )
    {
        $this->httpConnectorService = $httpConnectorService;
        $this->generateCommandService = $generateCommandService;
        $this->validationService = $validationService;
        $this->formatDataService = $formatDataService;
        $this->heliacalEventRepository = $heliacalEventRepository;
    }

    /**
     * Get astro data endpoint
     *
     * @param GetDataRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function getData(GetDataRequest $request)
    {
        while (Redis::get('refreshing_api_token_' . $request->email)) {
            sleep(2);
        }
        if (! ($dataQueries = $this->validationService->isJson($request->dataQueries))) {
            $this->changeResponseStatus(422);
            return response()->json(['error' => 'Not valid data have been received.'], $this->status);
        }

        $data = [];
        $heliacalEventsData = [];

        foreach ($dataQueries as $key => $dataQuery) {
            $swetestOptions = $this->httpConnectorService->connectSwetestOptions($dataQuery, $this->generateCommandService);

            if (isset($swetestOptions['error'])) {
                return response($swetestOptions['error'], 422);
            }

            $command = $this->generateCommandService->generateCommand('swetest', $swetestOptions);
            if ($command) {
                exec($command, $output);
                $data[$key] = $output;
            } else {
                $data[$key] = 'Not valid data have been received.';
                $this->changeResponseStatus(422);
            }
            $heliacalEventsData[$key] = $this->addHeliacalEventsData($dataQuery);
        }

        $data = $this->formatDataService->formatSwetestResult($data);

        foreach ($data as $key => $item) {
            $data[$key]['heliacalEventsData'] = $heliacalEventsData[$key];
        }

        return response(['data' => $data], 200);
    }

    /**
     * Change the response status
     *
     * @param int $status
     */
    protected function changeResponseStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * When there are relevant heliacal events data, adds it
     *
     * @param array $dataQuery
     * @return array
     */
    protected function addHeliacalEventsData(array $dataQuery): array
    {
        $locationData = explode(',', $dataQuery['houseTypes']);
        if ($city = City::where('long', $locationData[0])->where('lat', $locationData[1])->first()) {
            return $this->heliacalEventRepository->getHeliacalEventsData($city, $dataQuery['date']);
        }

        return [];
    }
}
