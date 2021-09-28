<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\candidacy;
use App\Models\Candidate;
use App\Models\Job;
use Illuminate\Http\Request;

class CandidacyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return candidacy::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return candidacy::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\candidacy  $candidacy
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $candidacy = candidacy::findOrFail($id);
        //dd($candidacy->getCandidate()->get());
        $candidacy->candidate_id = $candidacy->getCandidate()->get();
        $candidacy->job_id = $candidacy->getJob()->get();

        return $candidacy;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\candidacy  $candidacy
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $candidacy = candidacy::findOrFail($id);
        $candidacy->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\candidacy  $candidacy
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $candidacy = candidacy::findOrFail($id);
        $candidacy->delete();
    }

    public function ranking(int $idJob)
    {
        $ranking = array();
        $job = Job::findOrFail($idJob);
        // Peguei as candidaturas para essa vaga
        $candidatures = candidacy::query()->where('job_id', '=', $idJob)->get();
        foreach ($candidatures as $candidature) {
            $applicant = Candidate::query()->where('id', '=', $candidature->candidate_id)->first();
            $applicant = $this->calculateDistance($job, $applicant);
            $ranking[] = $applicant;
        }
        return $ranking;
    }

    public function calculateDistance(Job $job, Candidate $applicant)
    {
        $distances = [
            'ab' => 5,
            'ac' => 12,
            'ad' => 8,
            'ae' => 16,
            'af' => 16,
            'bc' => 7,
            'bd' => 3,
            'be' => 11,
            'bf' => 11,
            'cd' => 10,
            'ce' => 4,
            'cf' => 18,
            'de' => 10,
            'df' => 8,
            'ef' => 18
        ];

        $nv = $job->level;
        $nc = $applicant->level;
        $n = 100 - 25 * ($nv - $nc);

        if ($applicant->localization == $job->localization) {
            $d = 100;
        } else {
            $applicantDistance = $distances[strtolower($applicant->localization . $job->localization)] ?? $distances[strtolower($job->localization . $applicant->localization)];
            if ($applicantDistance >= 0 && $applicantDistance <= 5) {
                $d = 100;
            } elseif ($applicantDistance <= 10) {
                $d = 75;
            } elseif ($applicantDistance <= 15) {
                $d = 50;
            } elseif ($applicantDistance <= 20) {
                $d = 25;
            } else {
                $d = 0;
            }
        }

        $score = ($n + $d) / 2;
        $applicant->score = $score;

        return $applicant;
    }
}
