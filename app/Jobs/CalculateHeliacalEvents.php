<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\HeliacalEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Collection;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Services\Interfaces\ConvertDateServiceInterface;

class CalculateHeliacalEvents implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Collection
     */
    protected Collection $citiesChunk;

    /**
     * @var array
     */
    protected array $planetsData;

    /**
     * @var array
     */
    protected array $heliacalEventsData;

    /**
     * @var string
     */
    protected $commandBase;

    /**
     * @var array
     */
    protected array $heliacalEventTypes;

    /**
     * Create a new job instance.
     *
     * @param Collection $citiesChunk
     * @param array $planetsData
     * @param array $heliacalEventsData
     * @param string $commandBase
     * @param array $heliacalEventTypes
     */
    public function __construct(Collection $citiesChunk,
                                array $planetsData,
                                array $heliacalEventsData,
                                string $commandBase,
                                array $heliacalEventTypes
    )
    {
        $this->citiesChunk = $citiesChunk;
        $this->planetsData = $planetsData;
        $this->heliacalEventsData = $heliacalEventsData;

        $this->commandBase = $commandBase;
        $this->heliacalEventTypes = $heliacalEventTypes;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $eventsData = [];

        foreach ($this->planetsData as $planet) {
            foreach ($this->citiesChunk as $city) {
                if (!$this->heliacalEventsData[$planet['id']][$city->id]) {
                    exec( $this->commandBase .
                        $planet['code'] .
                        ' -geopos' . $city->long . ',' . $city->lat .
                        ' -hev',
                        $events
                    );

                    $eventsCount = count($events);
                    for ($i = 1; $i < $eventsCount; $i++) {
                        $data = explode(': ', $events[$i]);
                        $names = explode(' ', $data[0]);
                        if ($names[0] === 'no') {
                            continue;
                        }
                        $eventsData[] = [
                            'planet_id'   => $this->planetsData[$names[0]]['id'],
                            'expected_at' => str_replace(['/', '   '], ['-', ' '], trim(explode('UT', $data[1])[0])),
                            'city_id'     => $city->id,
                            'type_id'     => $this->heliacalEventTypes[$names[1] . $names[2]],
                            'visible_for' => trim($data[2]),
                        ];
                    }

                    $events = [];
                }
            }
        }

        try {
            $this->storeHeliacalEvents($eventsData);
        } catch (\Exception $e) {
            $this->processData();
        }
    }

    /**
     * @param \Throwable $exception
     */
    public function failed(\Throwable $exception)
    {
        logger($exception->getMessage());
        logger($exception->getTrace());
    }

    /**
     * Retry to get swetest data and save it
     *
     * @return void
     */
    protected function processData(): void
    {
        $eventsData = [];

        foreach ($this->planetsData as $planet) {
            foreach ($this->citiesChunk as $city) {
                if (!$this->heliacalEventsData) {
                    exec($this->commandBase .
                        $planet['code'] .
                        ' -geopos' . $city->long . ',' . $city->lat .
                        ' -hev',
                        $events
                    );

                    $eventsCount = count($events);
                    for ($i = 1; $i < $eventsCount; $i++) {
                        $data = explode(': ', $events[$i]);
                        $names = explode(' ', $data[0]);
                        $eventsData[] = [
                            'planet_id'   => $this->planetsData[$names[0]]['id'],
                            'expected_at' => str_replace(['/', '   '], ['-', ' '], $this->fixDate(trim(explode('UT', $data[1])[0]))),
                            'city_id'     => $city->id,
                            'type_id'     => $this->heliacalEventTypes[$names[1] . $names[2]],
                            'visible_for' => trim($data[2]),
                        ];
                    }

                    $events = [];
                }
            }
        }

        $this->storeHeliacalEvents($eventsData);
    }

    /**
     * Fix date
     *
     * @param string $expectedAt
     * @return string|string[]
     */
    protected function fixDate(string $expectedAt)
    {
        if (strpos($expectedAt, '24:00:00') !== false) {
            $expectedAt = explode('.', $expectedAt)[0];
            $expectedAt = str_replace('24:00:00', '00:00:00', $expectedAt);
            $expectedAt = Carbon::createFromFormat('Y-m-d H:i:s', $expectedAt);
            logger('24:00:00 replaced into expected at');

            return $expectedAt->addDay()->toDateTimeString();
        }

        return $expectedAt;
    }

    /**
     * @param array $eventsData
     */
    protected function storeHeliacalEvents(array $eventsData): void
    {
        HeliacalEvent::insert($eventsData);
    }
}
