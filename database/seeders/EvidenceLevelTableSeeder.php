<?php

namespace Database\Seeders;

use App\Models\Disease\EvidenceLevel;
use Illuminate\Database\Seeder;

class EvidenceLevelTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $levels = [
            [
                'name' => 'Level A — Strong Clinical Evidence (Multiple RCTs / Meta-Analyses)',
                'code' => 'GRADE-A',
                'description' => 'Highest level of evidence supported by multiple randomized controlled trials, systematic reviews, or gold-standard guidelines.',
                'badge_class' => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20',
                'rank' => 1,
            ],
            [
                'name' => 'Level B — Moderate Clinical Evidence (Well-designed Clinical Trials / Cohorts)',
                'code' => 'GRADE-B',
                'description' => 'Evidence derived from well-designed controlled trials, cohort studies, or single large randomized studies.',
                'badge_class' => 'bg-blue-500/10 text-blue-500 border-blue-500/20',
                'rank' => 2,
            ],
            [
                'name' => 'Level C — Observational / Consensus (Expert Opinion & Case Studies)',
                'code' => 'GRADE-C',
                'description' => 'Supported by clinical consensus, observational studies, retrospective series, or pathophysiological rationale.',
                'badge_class' => 'bg-amber-500/10 text-amber-500 border-amber-500/20',
                'rank' => 3,
            ],
            [
                'name' => 'Level D — Empirical / Traditional / Emerging Evidence',
                'code' => 'EMERGING',
                'description' => 'Traditional folk usage, early-stage in-vitro/animal studies, or anecdotal clinical reports requiring further validation.',
                'badge_class' => 'bg-purple-500/10 text-purple-500 border-purple-500/20',
                'rank' => 4,
            ],
        ];

        foreach ($levels as $level) {
            EvidenceLevel::updateOrCreate(['code' => $level['code']], $level);
        }
    }
}
