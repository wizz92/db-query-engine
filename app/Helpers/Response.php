<?php
namespace App\Helpers;
use Illuminate\Support\Collection;
use Illuminate\Http\JsonResponse;
use App\Helpers\BootstrapData\CheapEssayBootstrapData;
use App\Helpers\BootstrapData\CheapEssayWritings24BootstrapData;
use App\Helpers\BootstrapData\EduCashionBootstrapData;
use App\Helpers\BootstrapData\Essay5BootstrapData;
use App\Helpers\BootstrapData\EssayCockBootstrapData;
use App\Helpers\BootstrapData\WriteDomBootstrapData;
use App\Helpers\BootstrapData\NewWriteDomBootstrapData;
use App\Helpers\BootstrapData\EssayExpressBootstrapData;
use App\Helpers\BootstrapData\EssayStudioBootstrapData;
use App\Helpers\BootstrapData\EssaysOnLeadershipBootstrapData;
use App\Helpers\BootstrapData\JosephServiceBootstrapData;
use App\Helpers\BootstrapData\PaperCoachBootstrapData;
use App\Helpers\BootstrapData\EssayTyperBootstrapData;
use App\Helpers\BootstrapData\WritePaperForMeBootstrapData;
use App\Helpers\BootstrapData\RocketPaperBootstrapData;
use App\Helpers\BootstrapData\SpeedyWriterBootstrapData;
use App\Helpers\BootstrapData\SpeedyPaperBootstrapData;
use App\Helpers\BootstrapData\WayWriteBootstrapData;
use App\Helpers\BootstrapData\WriteCustomBootstrapData;
use App\Helpers\BootstrapData\WriteServicePaperBootstrapData;
use App\Helpers\BootstrapData\CustomWriterBootstrapData;
use App\Helpers\BootstrapData\WritingConsultantBootstrapData;
use App\Helpers\BootstrapData\EssayBlastBootstrapData;
use App\Helpers\BootstrapData\NursingEssayWritingBootstrapData;
use App\Helpers\BootstrapData\BestEssayCompanyBootstrapData;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\BootstrapData\CorrectMyWritingBootstrapData;
use App\Helpers\BootstrapData\CheapDissertationWritingBootstrapData;
use App\Helpers\BootstrapData\CheapDissertationsBootstrapData;
use App\Helpers\BootstrapData\Resume101BootstrapData;
use App\Helpers\BootstrapData\WriteYourPapersBootstrapData;
use App\Helpers\BootstrapData\Editing101BootstrapData;
use App\Helpers\BootstrapData\PapersWritingHelpBootstrapData;
use App\Helpers\BootstrapData\CollegePapersWritingBootstrapData;
use App\Helpers\BootstrapData\Service4LazyBootstrapData;
use App\Helpers\BootstrapData\ProEssaysBootstrapData;
use App\Helpers\BootstrapData\EssayTeriaBootstrapData;
// @rex I will optimize this later
use App\Helpers\BootstrapData\FerdigSkrevetNoBootstrapData;
use App\Helpers\BootstrapData\FerdigSkrevetComBootstrapData;
// @rex
use App\Helpers\BootstrapData\MyPaperEditBootstrapData;
use App\Helpers\BootstrapData\WritingSupportBootstrapData;
use App\Helpers\BootstrapData\SpeedyPaperChineseBootstrapData;
use App\Helpers\BootstrapData\HelpWritingCollegeEssaysBootstrapData;
use App\Helpers\BootstrapData\RocketPaperOrgBootstrapData;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class Response implements ResponseInterface
{
    use ResponsePresets;
    public $statuses = [
        'OK' => 200,
        'ERROR' => 400,
        'PROCESSING_PAYMENT' => 300,
        'UNAUTHORIZED_APP' => 401,
        'UNAUTHORIZED_USER' => 403,
        'WRONG_RESET_TOKEN' => 409,
        'WRONG_ROLE' => 410,
        'NOT_PERMITTED_USER' => 411,
        'SESSION_EXPIRED' => 418,
        'UNPROCESSABLE_ENTITY' => SymfonyResponse::HTTP_UNPROCESSABLE_ENTITY,
        'WRONG_DISCOUNT_CODE' => 413,
    ];


    protected $cache_static = 60*8;

    protected $cache_dynamic = 60*8;


    protected $rewrite_cache = false; // change to true if you want to disable caching of the bootstrap data;
    protected $data = [];
    protected $status = 200;
    protected $errors = [];
    protected $alerts = [];
    /**
     * @param int $dataName
     * @param Model|Collection|array $data
     * @return Response
     */
    public function setData($dataName, $data)
    {
        // add ability to send collection to data
        if ($data instanceof Model || $data instanceof Collection) {
            $data = $data->toArray();
        }
        if ($data === null) {
            $data = [];
        }
        if ($dataName) {
            $this->data[$dataName] = $data;
        } else {
            if (is_array($data) === false) {
                \Bugsnag::notifyError(
                    'Response',
                    "Incorrect response data type",
                    function ($report) use ($data) {
                        $report->setMetaData([
                            'data' => $data
                        ]);
                    }
                );
            }
            $this->data = array_merge($this->data, $data);
        }
        return $this;
    }
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }
    public function setErrors($errors)
    {
        if (!$errors) {
            return $this;
        }
        if (is_array($errors)) {
            $errors = isAssoc($errors) ? array_get($errors, 'errors', []) : $errors;
            ($this->status == 200 && $errors) ? $this->setStatus(400) : '';
            $this->errors = array_merge($this->errors, $errors);
            return $this;
        }
        if ($errors instanceof \Illuminate\Support\MessageBag) {
            foreach ($errors->toArray() as $key => $error) {
                $this->errors[] = $error[0];
            }
            ($this->status == 200 && $errors) ? $this->setStatus(400) : '';
            return $this;
        }
        $this->errors[] = $errors;
        ($this->status == 200 && $errors) ? $this->setStatus(400) : '';
        return $this;
    }
    public function setAlerts($alerts)
    {
        if (is_array($alerts)) {
            array_merge($this->alerts, $alerts);
        }
        $this->alerts[] = $alerts;
        return $this;
    }
    /**
    * Gets global errors and alerts from session and adds them to response
    * @author rex
    * @return void
    */
    private function manageGlobalErrorsAndAlerts()
    {
        $errors = session()->pull('globalErrors');
        $alerts = session()->pull('globalAlerts');
        if (isset($errors)) {
            $this->setErrors($errors);
        }
        if (isset($alerts)) {
            foreach ($alerts as $key => $alert) {
                $this->setAlerts($alert);
            }
        }
    }

    /**
     * this function needed to clear previews response data in case we using it as singleton
     */
    private function resetResponseData(){
        $this->data = [];
        $this->status = 200;
        $this->errors = [];
        $this->alerts = [];
    }

    public function get()
    {
        $this->manageGlobalErrorsAndAlerts();
        $r = [
            'data' => $this->data,
            'status' => $this->status,
            'errors' => $this->errors,
            'alerts' => $this->alerts,
        ];
        if(config('app.debug'))
        {
            $r['queries_count'] = qdd('count');
        }
        $res = new JsonResponse($r);
        if (requestOriginIsValid()) {
            $res = setResponseHeaders($res);
        }

        $this->resetResponseData();

        return $res;
    }
}
