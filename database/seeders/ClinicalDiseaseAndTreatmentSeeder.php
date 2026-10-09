<?php

namespace Database\Seeders;

use App\Models\Disease\Disease;
use App\Models\Disease\DiseaseCategory;
use App\Models\Disease\DiseaseExercise;
use App\Models\Disease\DiseaseFood;
use App\Models\Disease\DiseaseSymptom;
use App\Models\Disease\DiseaseTreatment;
use App\Models\Disease\EvidenceLevel;
use App\Models\Disease\Symptom;
use App\Models\Disease\Treatment;
use App\Models\Disease\TreatmentType;
use App\Models\Exercise\Exercise;
use App\Models\Food\Food;
use Illuminate\Database\Seeder;

class ClinicalDiseaseAndTreatmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Fetch Types & Evidence Levels
        $pharmacotherapy = TreatmentType::where('slug', 'pharmacotherapy')->first();
        $lifestyleDiet = TreatmentType::where('slug', 'lifestyle-diet')->first();
        $physicalTherapy = TreatmentType::where('slug', 'physical-therapy-exercise')->first();
        $herbalNutra = TreatmentType::where('slug', 'herbal-nutraceutical')->first();
        $behavioral = TreatmentType::where('slug', 'psychotherapy-behavioral')->first();
        $homeRemedy = TreatmentType::where('slug', 'home-remedy-supportive')->first();

        $gradeA = EvidenceLevel::where('code', 'GRADE-A')->first();
        $gradeB = EvidenceLevel::where('code', 'GRADE-B')->first();
        $gradeC = EvidenceLevel::where('code', 'GRADE-C')->first();

        // 2. Master Treatments Catalog (Option 2: Reusable master treatments)
        $treatmentsData = [
            'dash-diet' => [
                'treatment_type_id' => $lifestyleDiet->id,
                'evidence_level_id' => $gradeA->id,
                'name' => 'DASH Dietary Protocol (Dietary Approaches to Stop Hypertension)',
                'slug' => 'dash-dietary-protocol',
                'description' => 'A structured dietary plan emphasizing potassium, magnesium, calcium, and dietary fiber while strictly limiting sodium to under 1,500-2,300 mg daily.',
                'mechanism_of_action' => 'Enhances natriuresis, promotes systemic vasodilation, reduces sympathetic drive, and lowers vascular wall stiffness.',
                'standard_protocol' => 'Abundant whole grains, vegetables, low-fat dairy, lean poultry, legumes, and nuts. Limit processed meats and saturated fats.',
                'side_effects' => 'Transient mild bloating during initial fiber increase.',
                'cautions_warnings' => 'Monitor potassium levels in individuals with advanced chronic kidney disease.',
            ],
            'zone2-aerobic' => [
                'treatment_type_id' => $physicalTherapy->id,
                'evidence_level_id' => $gradeA->id,
                'name' => 'Zone 2 Low-Intensity Sustained Aerobic Training',
                'slug' => 'zone-2-aerobic-training',
                'description' => 'Continuous steady-state cardiovascular conditioning maintaining heart rate within 60-70% of maximum (lactate clearance zone).',
                'mechanism_of_action' => 'Expands capillary density in skeletal muscle, upregulates mitochondrial biogenesis, and enhances systemic endothelial nitric oxide release.',
                'standard_protocol' => '30 to 45 minutes per session, 4 to 5 times per week (aiming for 150-180 minutes weekly total).',
                'side_effects' => 'Temporary muscular soreness.',
                'cautions_warnings' => 'Keep exertion at conversational pace. Avoid sudden supramaximal cardiac spikes in unmonitored patients.',
            ],
            'ace-inhibitor-ramipril' => [
                'treatment_type_id' => $pharmacotherapy->id,
                'evidence_level_id' => $gradeA->id,
                'name' => 'Angiotensin-Converting Enzyme (ACE) Inhibitor Therapy (Ramipril / Lisinopril)',
                'slug' => 'ace-inhibitor-therapy',
                'description' => 'Front-line antihypertensive medication blocking the conversion of Angiotensin I to Angiotensin II.',
                'mechanism_of_action' => 'Attenuates vasoconstriction and aldosterone-mediated fluid retention, inducing arteriolar dilation and decreasing systemic vascular resistance.',
                'standard_protocol' => 'Initial dosage 2.5 - 5 mg daily titrating upward based on ambulatory blood pressure monitoring.',
                'side_effects' => 'Dry hacking cough (10-15%), dizziness, hyperkalemia.',
                'cautions_warnings' => 'Strictly contraindicated in pregnancy and bilateral renal artery stenosis. Monitor serum creatinine and potassium.',
            ],
            'magnesium-glycinate' => [
                'treatment_type_id' => $herbalNutra->id,
                'evidence_level_id' => $gradeB->id,
                'name' => 'Magnesium Glycinate / Citrate Supplementation',
                'slug' => 'magnesium-supplementation',
                'description' => 'High-bioavailability elemental magnesium targeting vascular smooth muscle relaxation and cellular electrolyte homeostasis.',
                'mechanism_of_action' => 'Functions as a natural calcium channel antagonist in vascular smooth muscle cells and suppresses catecholamine discharge.',
                'standard_protocol' => '300 to 400 mg of elemental magnesium taken daily, preferably with evening meal.',
                'side_effects' => 'Loose stools or osmotic diarrhea at high doses with non-chelated forms.',
                'cautions_warnings' => 'Dose reduction advised in renal impairment.',
            ],
            'low-gi-mediterranean' => [
                'treatment_type_id' => $lifestyleDiet->id,
                'evidence_level_id' => $gradeA->id,
                'name' => 'Low-Glycemic Mediterranean Whole Food Nutritional Protocol',
                'slug' => 'low-gi-mediterranean-diet',
                'description' => 'Nutritional plan prioritizing high monounsaturated fatty acids (extra virgin olive oil), complex unrefined carbs, plant sterols, and clean proteins.',
                'mechanism_of_action' => 'Blunts acute glycemic excursions, dampens postprandial hyperinsulinemia, and reduces hepatic de novo lipogenesis.',
                'standard_protocol' => 'Plate composed of 50% non-starchy vegetables, 25% lean protein, and 25% fiber-rich legumes or intact grains, dressed with healthy fats.',
                'side_effects' => 'Initial carbohydrate withdrawal fatigue lasting 3-5 days.',
                'cautions_warnings' => 'Coordinate with medication dosages to avoid hypoglycemia in patients on exogenous insulin or secretagogues.',
            ],
            'resistance-training' => [
                'treatment_type_id' => $physicalTherapy->id,
                'evidence_level_id' => $gradeA->id,
                'name' => 'Progressive Resistance Training (Hypertrophy & Glucose Disposal)',
                'slug' => 'progressive-resistance-training',
                'description' => 'Structured resistance training recruiting major skeletal muscle motor units across compound movement patterns.',
                'mechanism_of_action' => 'Triggers insulin-independent GLUT4 glucose transporter translocation to myocyte membranes, expanding glycogen storage reserves.',
                'standard_protocol' => 'Full-body or split routine 3 days weekly, 3-4 sets of 8-12 repetitions targeting major muscle groups.',
                'side_effects' => 'Delayed onset muscle soreness (DOMS).',
                'cautions_warnings' => 'Maintain proper breathing technique; avoid prolonged Valsalva maneuver during acute blood pressure surges.',
            ],
            'metformin-therapy' => [
                'treatment_type_id' => $pharmacotherapy->id,
                'evidence_level_id' => $gradeA->id,
                'name' => 'Metformin Biguanide Therapy',
                'slug' => 'metformin-biguanide-therapy',
                'description' => 'First-line pharmaceutical antihyperglycemic agent for metabolic regulation and insulin sensitization.',
                'mechanism_of_action' => 'Activates hepatic AMP-activated protein kinase (AMPK), repressing gluconeogenesis and enhancing peripheral muscle glucose uptake.',
                'standard_protocol' => '500 mg daily with food, slowly titrating to 1,000 mg twice daily (extended-release formulations preferred).',
                'side_effects' => 'Nausea, abdominal discomfort, diarrhea, vitamin B12 malabsorption.',
                'cautions_warnings' => 'Monitor renal function (eGFR < 30 mL/min is contraindicated). Periodically check B12 levels.',
            ],
            'berberine-hcl' => [
                'treatment_type_id' => $herbalNutra->id,
                'evidence_level_id' => $gradeB->id,
                'name' => 'Berberine Hydrochloride Botanical Protocol',
                'slug' => 'berberine-hydrochloride-protocol',
                'description' => 'Bioactive isoquinoline alkaloid isolated from Coptis chinensis and Berberis aristata with demonstrated metabolic effects.',
                'mechanism_of_action' => 'Activates AMPK pathway in parallel to metformin, upregulates LDLR expression, and modulates the intestinal microbiome.',
                'standard_protocol' => '500 mg orally 2 to 3 times daily taken 15-20 minutes prior to carbohydrate-containing meals.',
                'side_effects' => 'Constipation or cramping at excessive dosages.',
                'cautions_warnings' => 'Inhibits CYP3A4; check interactions with statins or immunosuppressants.',
            ],
            'joint-mobility-rehab' => [
                'treatment_type_id' => $physicalTherapy->id,
                'evidence_level_id' => $gradeA->id,
                'name' => 'Targeted Joint Mobility & Kinetic Chain Rehabilitation',
                'slug' => 'joint-mobility-kinetic-chain-rehab',
                'description' => 'Non-impact range-of-motion therapy, isometric stabilization, and peri-articular muscular balance conditioning.',
                'mechanism_of_action' => 'Stimulates synovial fluid circulation for cartilage chondrocyte nutrition, reduces shear forces, and improves joint congruency.',
                'standard_protocol' => 'Daily 15-20 minute sessions of low-load active assisted range of motion, stretching, and closed-chain stabilization.',
                'side_effects' => 'Mild joint fatigue.',
                'cautions_warnings' => 'Avoid working into acute sharp joint pinching or bone-on-bone pain.',
            ],
            'omega3-antiinflammatory' => [
                'treatment_type_id' => $herbalNutra->id,
                'evidence_level_id' => $gradeA->id,
                'name' => 'Concentrated High-EPA/DHA Omega-3 Fatty Acids',
                'slug' => 'omega-3-epa-dha-protocol',
                'description' => 'Marine-derived eicosapentaenoic acid (EPA) and docosahexaenoic acid (DHA) standardized triglycerides.',
                'mechanism_of_action' => 'Competitively displaces arachidonic acid, giving rise to resolving anti-inflammatory mediators (resolvins, protectins).',
                'standard_protocol' => '2,000 to 3,000 mg combined EPA+DHA daily with fat-containing meals.',
                'side_effects' => 'Mild fishy aftertaste; transient mild burping.',
                'cautions_warnings' => 'Mild antiplatelet effect; caution with concurrent anticoagulants prior to scheduled surgical procedures.',
            ],
            'acid-reflux-lifestyle' => [
                'treatment_type_id' => $homeRemedy->id,
                'evidence_level_id' => $gradeB->id,
                'name' => 'Gastroesophageal Postural & Chrono-Dietary Protocol',
                'slug' => 'gastroesophageal-postural-dietary-protocol',
                'description' => 'Postprandial upright positioning, avoiding recumbency for 3 hours post-meal, and elevating head of bed by 15-20 cm.',
                'mechanism_of_action' => 'Harnesses gravity to prevent retrograde intra-gastric acid migration across the lower esophageal sphincter (LES).',
                'standard_protocol' => 'Maintain upright posture after eating, finish dinner 3+ hours before bedtime, sleep on left lateral decubitus position with wedge elevation.',
                'side_effects' => 'None.',
                'cautions_warnings' => 'Using multiple pillows bends the neck instead of elevating the torso; use an authentic incline wedge.',
            ],
            'cbt-sleep-stress' => [
                'treatment_type_id' => $behavioral->id,
                'evidence_level_id' => $gradeA->id,
                'name' => 'Cognitive Behavioral Therapy & Parasympathetic Reset Protocol',
                'slug' => 'cbt-parasympathetic-reset',
                'description' => 'Evidence-based behavioral conditioning integrating stimulus control, sleep hygiene, and physiological slow-paced diaphragmatic breathing.',
                'mechanism_of_action' => 'Dampens hypothalamic-pituitary-adrenal (HPA) axis overdrive, reducing circulating cortisol and downregulating central autonomic tone.',
                'standard_protocol' => '10-15 minutes of 4-7-8 or resonant 5.5 breaths/minute practice twice daily and structured bedtime stimulus control.',
                'side_effects' => 'None.',
                'cautions_warnings' => 'Consistent daily execution is required for measurable neuro-autonomic remodeling.',
            ],
        ];

        $treatments = [];
        foreach ($treatmentsData as $key => $data) {
            $treatments[$key] = Treatment::updateOrCreate(['slug' => $data['slug']], $data);
        }

        // 3. Diseases Catalog
        // Disease 1: Essential Hypertension (High Blood Pressure)
        $hypertension = Disease::updateOrCreate(
            ['slug' => 'essential-hypertension'],
            [
                'name' => 'High Blood Pressure (Hypertension)',
                'slug' => 'essential-hypertension',
                'overview' => 'High blood pressure is when the force of your blood pushing against your artery walls is consistently too high. Over time, this puts extra strain on your heart and blood vessels.',
                'causes' => 'Too much salt in the diet, lack of physical activity, being overweight, chronic stress, smoking, or family genetics.',
                'risk_factors' => 'High salt intake, lack of exercise, stress, obesity, and aging.',
                'prevention' => 'Eat a balanced low-salt diet, exercise regularly (like brisk walking 30 minutes a day), manage stress, and avoid smoking.',
            ]
        );

        // Disease 2: Type 2 Diabetes
        $diabetes = Disease::updateOrCreate(
            ['slug' => 'type-2-diabetes-mellitus'],
            [
                'name' => 'Type 2 Diabetes',
                'slug' => 'type-2-diabetes-mellitus',
                'overview' => 'A condition where your body either does not make enough insulin or cannot properly use the insulin it makes, leading to elevated blood sugar levels.',
                'causes' => 'Insulin resistance from excess body fat, lack of physical activity, and a diet high in refined sugars and processed foods.',
                'risk_factors' => 'Carrying extra weight around the belly, physical inactivity, sugary diets, and family history.',
                'prevention' => 'Regular exercise, strength training, eating whole unprocessed foods, and maintaining a healthy weight.',
            ]
        );

        // Disease 3: Osteoarthritis (Joint Pain)
        $osteoarthritis = Disease::updateOrCreate(
            ['slug' => 'osteoarthritis'],
            [
                'name' => 'Osteoarthritis (Joint Pain & Stiffness)',
                'slug' => 'osteoarthritis',
                'overview' => 'The most common form of arthritis, caused by the gradual wearing down of the protective cartilage that cushions the ends of your bones in the joints.',
                'causes' => 'Wear and tear over time, repetitive stress on joints, previous joint injuries, or excess body weight putting strain on weight-bearing joints.',
                'risk_factors' => 'Older age, past joint injuries, repetitive movement jobs, and excess weight.',
                'prevention' => 'Stay active with low-impact exercises (like walking or swimming), strengthen surrounding muscles, and maintain a healthy weight.',
            ]
        );

        // Disease 4: Acid Reflux / Heartburn (GERD)
        $gerd = Disease::updateOrCreate(
            ['slug' => 'gerd-acid-reflux'],
            [
                'name' => 'Acid Reflux / Heartburn (GERD)',
                'slug' => 'gerd-acid-reflux',
                'overview' => 'A digestive issue where stomach acid repeatedly flows back up into the tube connecting your mouth and stomach (esophagus), causing a burning chest sensation.',
                'causes' => 'The muscle valve between the stomach and esophagus relaxes too often, or pressure in the stomach pushes acid upward.',
                'risk_factors' => 'Eating large meals, lying down right after eating, obesity, fatty or spicy foods, and caffeine.',
                'prevention' => 'Eat smaller meals, wait 3 hours after eating before sleeping, elevate your head when sleeping, and avoid trigger foods.',
            ]
        );

        // 4. Link Symptoms to Diseases (Normalized symptom linkage)
        $symptomsMap = Symptom::all()->keyBy('slug');

        // Hypertension Symptoms
        $this->attachSymptom($hypertension, $symptomsMap['high-blood-pressure-spikes'] ?? null, true, 'Constant', 'Cardinal biomarker and hallmark sign.');
        $this->attachSymptom($hypertension, $symptomsMap['throbbing-unilateral-headache'] ?? null, false, 'Occasional', 'Occurs typically during acute morning pressure surges.');
        $this->attachSymptom($hypertension, $symptomsMap['chest-pain-angina'] ?? null, false, 'Occasional', 'Indicates elevated cardiac afterload or ischemia.');
        $this->attachSymptom($hypertension, $symptomsMap['heart-palpitations-arrhythmia'] ?? null, false, 'Occasional', 'Related to sympathetic activation.');

        // Diabetes Symptoms
        $this->attachSymptom($diabetes, $symptomsMap['excessive-thirst-polydipsia'] ?? null, true, 'Constant', 'Driven by osmotic diuresis and cellular dehydration.');
        $this->attachSymptom($diabetes, $symptomsMap['frequent-urination-polyuria'] ?? null, true, 'Frequent', 'Kidney threshold exceeded by blood glucose > 180 mg/dL.');
        $this->attachSymptom($diabetes, $symptomsMap['unexplained-fatigue-energy-crashes'] ?? null, false, 'Common', 'Cellular energy deficit despite high circulating glucose.');
        $this->attachSymptom($diabetes, $symptomsMap['brain-fog-poor-focus'] ?? null, false, 'Common', 'Fluctuating cerebral glucose dynamics.');

        // Osteoarthritis Symptoms
        $this->attachSymptom($osteoarthritis, $symptomsMap['joint-stiffness-morning-pain'] ?? null, true, 'Frequent', 'Stiffness typically persists < 30 minutes upon rising (gelling phenomenon).');
        $this->attachSymptom($osteoarthritis, $symptomsMap['reduced-joint-range-of-motion'] ?? null, true, 'Common', 'Progression caused by osteophytes and joint space narrowing.');
        $this->attachSymptom($osteoarthritis, $symptomsMap['muscle-cramps-spasms'] ?? null, false, 'Occasional', 'Compensatory splinting of surrounding muscle groups.');

        // GERD Symptoms
        $this->attachSymptom($gerd, $symptomsMap['heartburn-acid-regurgitation'] ?? null, true, 'Frequent', 'Hallmark retrosternal burning radiating toward throat.');
        $this->attachSymptom($gerd, $symptomsMap['epigastric-bloating-fullness'] ?? null, false, 'Common', 'Worsened by delayed gastric emptying.');
        $this->attachSymptom($gerd, $symptomsMap['persistent-night-cough'] ?? null, false, 'Occasional', 'Micro-aspiration or vagal reflex triggered by nocturnal acid.');

        // 5. Link Master Treatments to Diseases (Option 2 Pivot Linkage)
        // Hypertension Treatments
        $this->attachTreatment($hypertension, $treatments['dash-diet'], true, 'First-line Nutritional Therapy', 'Adopt strict sodium restriction (<1,500mg/day) paired with potassium-dense foods.', 'Daily meals', 'Lifelong');
        $this->attachTreatment($hypertension, $treatments['zone2-aerobic'], false, 'First-line Lifestyle Medicine', 'Engage in continuous brisk walking, light jogging, or cycling at conversational pace.', '4-5 sessions/week (30-45 min)', 'Ongoing');
        $this->attachTreatment($hypertension, $treatments['ace-inhibitor-ramipril'], true, 'First-line Pharmacotherapy', 'Prescribed by clinician to protect renal and cardiac function.', '2.5mg - 10mg once daily', 'Continuous clinical evaluation');
        $this->attachTreatment($hypertension, $treatments['magnesium-glycinate'], false, 'Adjunctive Nutraceutical', 'Take with evening meal to assist smooth muscle relaxation and sleep.', '300-400 mg daily', '3-6 months minimum');
        $this->attachTreatment($hypertension, $treatments['cbt-sleep-stress'], false, 'Adjunctive Behavioral Protocol', 'Perform 10 minutes of paced 5.5 breaths/minute diaphragmatic breathing morning and evening.', 'Twice daily', 'Ongoing');

        // Diabetes Treatments
        $this->attachTreatment($diabetes, $treatments['low-gi-mediterranean'], true, 'Primary Remission Nutritional Protocol', 'Prioritize unprocessed leafy greens, lean protein, healthy fats, and low glycemic carbs to restore insulin sensitivity.', 'Daily meals', 'Lifelong sustainable pattern');
        $this->attachTreatment($diabetes, $treatments['resistance-training'], false, 'First-line Physical Therapy', 'Perform compound resistance exercises to increase non-insulin glucose clearance in skeletal muscle.', '3 sessions per week', 'Permanent lifestyle routine');
        $this->attachTreatment($diabetes, $treatments['metformin-therapy'], true, 'First-line Pharmacotherapy', 'Reduces hepatic glucose output and supports insulin action.', '500-1000mg twice daily with meals', 'Under physician monitoring');
        $this->attachTreatment($diabetes, $treatments['berberine-hcl'], false, 'Adjunctive Botanical Therapy', 'Natural AMPK activator to take immediately before main carbohydrate meals.', '500 mg 2-3x daily before meals', '8-12 weeks trial');

        // Osteoarthritis Treatments
        $this->attachTreatment($osteoarthritis, $treatments['joint-mobility-rehab'], true, 'First-line Rehabilitation', 'Low-impact active range of motion and isometric quadriceps strengthening.', 'Daily 20-minute sessions', 'Continuous');
        $this->attachTreatment($osteoarthritis, $treatments['omega3-antiinflammatory'], false, 'Supportive Nutraceutical', 'Suppresses joint inflammatory cytokines (IL-1beta, TNF-alpha).', '2,000-3,000 mg EPA+DHA daily', 'Ongoing');
        $this->attachTreatment($osteoarthritis, $treatments['zone2-aerobic'], false, 'Supportive Aerobic Conditioning', 'Low-impact pool walking, stationary cycling, or elliptical to prevent joint pounding.', '3 sessions per week', 'Continuous');

        // GERD Treatments
        $this->attachTreatment($gerd, $treatments['acid-reflux-lifestyle'], true, 'First-line Lifestyle Cure', 'Maintain 3-hour fasting window prior to bed; use an inclined torso wedge while sleeping.', 'Every evening & meal', 'Permanent habit');
        $this->attachTreatment($gerd, $treatments['cbt-sleep-stress'], false, 'Adjunctive Behavioral Support', 'Stress exacerbates visceral esophageal hypersensitivity; practice diaphragmatic relaxation.', '10 minutes daily', 'Ongoing');

        // 6. Link Foods to Diseases (From existing food table)
        // Let's find specific foods in the food table
        $foods = Food::all();

        // Beneficial / therapeutic foods
        $brownBread = $foods->firstWhere('name', 'Bread, Brown');
        $sweetBiscuit = $foods->firstWhere('name', 'Biscuit, Sweet');
        $whiteBread = $foods->firstWhere('name', 'Bread, White');
        $nuts = $foods->first(fn($f) => stripos($f->name, 'Nut') !== false);
        $cornflakes = $foods->first(fn($f) => stripos($f->name, 'corn') !== false);

        if ($brownBread) {
            $this->attachFood($hypertension, $brownBread, 'recommended', 'Provides complex carbohydrates and dietary fiber without excessive added sodium.', '1-2 slices with breakfast');
            $this->attachFood($diabetes, $brownBread, 'recommended', 'Low to moderate glycemic impact compared to refined white flour products.', '1 portion max with protein');
        }

        if ($nuts) {
            $this->attachFood($hypertension, $nuts, 'therapeutic', 'Rich in monounsaturated fats, magnesium, and arginine, supporting vascular nitric oxide generation.', 'Handful (30g) raw daily');
            $this->attachFood($diabetes, $nuts, 'therapeutic', 'High healthy fat and protein content blunts blood sugar surges and curbs cravings.', '30g as snack between meals');
            $this->attachFood($osteoarthritis, $nuts, 'recommended', 'Anti-inflammatory unsaturated fatty acids help lower systemic inflammatory markers.', '30g daily');
        }

        if ($sweetBiscuit) {
            $this->attachFood($diabetes, $sweetBiscuit, 'avoid', 'Ultra-processed simple sugars and refined flour cause acute glycemic spikes and worsen insulin resistance.', 'Avoid completely');
            $this->attachFood($gerd, $sweetBiscuit, 'avoid', 'High sugar and saturated fat delay stomach emptying and trigger lower esophageal sphincter relaxations.', 'Avoid, especially in the evening');
        }

        if ($whiteBread) {
            $this->attachFood($diabetes, $whiteBread, 'limit', 'High glycemic index of ~75 rapidly hydrolyzes into glucose in the small intestine.', 'Substitute with whole sprouted grains');
        }

        // 7. Link Exercises to Diseases (From existing exercise table)
        $exercises = Exercise::all();
        $benchPress = $exercises->firstWhere('name', 'Barbell Bench Press');
        $pecDec = $exercises->firstWhere('name', 'Pec Dec Fly');
        $dumbbellPress = $exercises->firstWhere('name', 'Flat Dumbbell Press');

        if ($benchPress) {
            $this->attachExercise($hypertension, $benchPress, 'caution', 'Safe when performed with controlled breathing; strictly avoid heavy 1-rep-max Valsalva breath-holding which causes acute arterial pressure spikes.', 'Exhale smoothly on pressing exertion, inhale on lowering.');
            $this->attachExercise($diabetes, $benchPress, 'recommended', 'Upper body compound resistance exercise activates large pectoral and triceps motor units, boosting peripheral GLUT4 glucose uptake.', '3-4 sets of 8-12 repetitions, 2 minutes rest between sets.');
            $this->attachExercise($osteoarthritis, $benchPress, 'recommended', 'Non-weight-bearing on knee/hip joints; maintains upper body bone density and muscular strength.', 'Adjust bench angle for shoulder comfort.');
        }

        if ($pecDec) {
            $this->attachExercise($hypertension, $pecDec, 'recommended', 'Machine-guided isolation exercise with lower axial pressure and predictable biomechanics.', 'Moderate weight, 12-15 repetitions.');
            $this->attachExercise($diabetes, $pecDec, 'recommended', 'Great for metabolic fatigue resistance and glycogen depletion.', '12-15 reps per set.');
        }

        if ($dumbbellPress) {
            $this->attachExercise($gerd, $dumbbellPress, 'caution', 'Lying completely flat or prone immediately after meals can provoke acid reflux into the esophagus. Perform in a semi-inclined position (30+ degrees) and wait at least 2 hours after eating.', 'Set incline bench to at least 30 degrees angle.');
        }
    }

    private function attachSymptom(Disease $disease, ?Symptom $symptom, bool $isPrimary, string $frequency, string $notes): void
    {
        if (!$symptom) return;

        DiseaseSymptom::updateOrCreate(
            ['disease_id' => $disease->id, 'symptom_id' => $symptom->id],
            [
                'is_primary' => $isPrimary,
                'frequency' => $frequency,
                'notes' => $notes,
            ]
        );
    }

    private function attachTreatment(Disease $disease, ?Treatment $treatment, bool $isPrimaryCure, string $lineOfTreatment, string $instructions, string $dosage, string $duration): void
    {
        if (!$treatment) return;

        DiseaseTreatment::updateOrCreate(
            ['disease_id' => $disease->id, 'treatment_id' => $treatment->id],
            [
                'is_primary_cure' => $isPrimaryCure,
                'line_of_treatment' => $lineOfTreatment,
                'specific_instructions' => $instructions,
                'dosage_or_frequency' => $dosage,
                'expected_duration' => $duration,
            ]
        );
    }

    private function attachFood(Disease $disease, ?Food $food, string $recommendationType, string $rationale, string $servingNotes): void
    {
        if (!$food) return;

        DiseaseFood::updateOrCreate(
            ['disease_id' => $disease->id, 'food_id' => $food->id],
            [
                'recommendation_type' => $recommendationType,
                'rationale' => $rationale,
                'serving_notes' => $servingNotes,
            ]
        );
    }

    private function attachExercise(Disease $disease, ?Exercise $exercise, string $safetyType, string $instructions, string $modifications = null): void
    {
        if (!$exercise) return;

        DiseaseExercise::updateOrCreate(
            ['disease_id' => $disease->id, 'exercise_id' => $exercise->id],
            [
                'safety_type' => $safetyType,
                'instructions' => $instructions,
                'modifications' => $modifications,
            ]
        );
    }
}
