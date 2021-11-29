<?php

namespace App\Http\Requests\JobTrackers;

use Illuminate\Foundation\Http\FormRequest;

class RetryJobRequest extends FormRequest
{
    use InputRules;

    private $jobTracker;

    public function getJobTracker()
    {
        if ($this->jobTracker) return $this->jobTracker;

        $id = $this->input('id') ?: $this->input('job_tracker_id');
        $jobTracker = JobTracker::findOrFail($id);

        return $this->model = $this->jobTracker = $jobTracker;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->getJobTracker();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
