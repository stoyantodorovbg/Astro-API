<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Support\Facades\Redis;
use App\Http\Requests\GetDataRequest;
use App\Services\Interfaces\FormatDataServiceInterface;
use App\Services\Interfaces\ValidationServiceInterface;
use App\Services\Interfaces\ExtractDataServiceInterface;
use App\Services\Interfaces\CalculateDataServiceInterface;
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
     * @var CalculateDataServiceInterface
     */
    protected $calculateDataService;

    /**
     * @var ExtractDataServiceInterface
     */
    protected ExtractDataServiceInterface $extractDataService;

    /**
     * DataController constructor.
     *
     * @param HttpConnectorServiceInterface $httpConnectorService
     * @param GenerateCommandServiceInterface $generateCommandService
     * @param ValidationServiceInterface $validationService
     * @param FormatDataServiceInterface $formatDataService
     * @param HeliacalEventRepositoryInterface $heliacalEventRepository
     * @param CalculateDataServiceInterface $calculateDataService
     */
    public function __construct(HttpConnectorServiceInterface $httpConnectorService,
                                GenerateCommandServiceInterface $generateCommandService,
                                ValidationServiceInterface $validationService,
                                FormatDataServiceInterface $formatDataService,
                                HeliacalEventRepositoryInterface $heliacalEventRepository,
                                CalculateDataServiceInterface $calculateDataService,
                                ExtractDataServiceInterface $extractDataService
    )
    {
        $this->httpConnectorService = $httpConnectorService;
        $this->generateCommandService = $generateCommandService;
        $this->validationService = $validationService;
        $this->formatDataService = $formatDataService;
        $this->heliacalEventRepository = $heliacalEventRepository;
        $this->calculateDataService = $calculateDataService;
        $this->extractDataService = $extractDataService;
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
        $tropicalMonthsData = [];
        $currentMoonMonth = '';

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

            $tropicalMonthsData[$key] = $this->calculateDataService->tropicalMonthsData($dataQuery);

            $locationData = explode(',', $dataQuery['houseTypes']);

            if ($city = City::where('long', $locationData[0])->where('lat', $locationData[1])->first()) {
                $isNight = $this->formatDataService->isNightResult($data[$key]);
                $heliacalEventsData[$key] = $this->heliacalEventRepository->getHeliacalEventsData($city, $dataQuery['date']);
                $currentMoonMonth = $this->calculateDataService->currentMoonMonth($city, $dataQuery, $isNight);
                $currentMoonDay = $this->calculateDataService->currentMoonDay($city, $dataQuery);
            }

            $data[$key] = $this->formatDataService->formatSwetestResult($data[$key]);
            $data[$key]['heliacalEventsData'] = $heliacalEventsData[$key] ?? [];
            $data[$key]['tropicalMonthsData'] = $tropicalMonthsData[$key] ?? [];
            $data[$key]['currentMoonMonth'] = $currentMoonMonth ?? '';
            $data[$key]['isNight'] = isset($isNight) ?  (string) (int) $isNight : '';
            $data[$key]['currentMoonDay'] = isset($currentMoonDay) ? (string) $currentMoonDay: '';
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
}
