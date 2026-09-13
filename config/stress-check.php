<?php

/**
 * Stress Check Configuration
 * 
 * This configuration file contains:
 * - Groups mapping: Groups 1-18 (Parts A,B,C), Groups 19-42 (Parts D,E,F,G,H) with calculation formulas
 * - Conversion table: Converts calculated values to scores (1-5 or 1-4) for each group
 * 
 * Reference: documents/score.md, group_stress_check_result.md
 */

return [
    /**
     * 18 Groups Mapping
     * Maps questions to groups with calculation formulas
     * 
     * Structure:
     * - group_number: Group identifier (1-18)
     * - part: Part letter (A-H)
     * - formula_type: Type of formula:
     *   * 'direct_sum': Sum of questions directly (e.g., No.1+No.2+No.3)
     *   * 'subtract_sum': base_value - sum_of_questions (e.g., 15-(No.1+No.2+No.3))
     *   * 'subtract_single': base_value - single_question (e.g., 5-No.7)
     *   * 'subtract_sum_add': base_value - sum_of_questions + add_question (e.g., 10-(No.12+No.13)+No.14)
     * - questions: Array of question keys (e.g., ['a.q1', 'a.q2', 'a.q3'])
     * - base_value: Base value for calculation (0 for direct_sum, e.g., 15 for subtract_sum)
     * - add_question: Optional question key to add (for subtract_sum_add formula type)
     * - result_key: Key in result field (e.g., 'psychological_workload_quantity')
     * - result_group: Group in result ('group_a'..'group_h')
     * 
     * Reference: bugs_analytics/score.md - "Cách tính điểm" section
     */
    'groups_mapping' => [
        // Group 1: Part A - Psychological workload quantity (仕事の量)
        1 => [
            'part' => 'A',
            'formula_type' => 'subtract_sum',
            'base_value' => 15,
            'questions' => ['a.q1', 'a.q2', 'a.q3'],
            'result_key' => 'psychological_workload_quantity',
            'result_group' => 'group_a',
        ],
        
        // Group 2: Part A - Psychological workload quality (仕事の質)
        // Formula: 15−(No.4+No.5+No.6)
        2 => [
            'part' => 'A',
            'formula_type' => 'subtract_sum',
            'base_value' => 15,
            'questions' => ['a.q4', 'a.q5', 'a.q6'],
            'result_key' => 'psychological_workload_quality',
            'result_group' => 'group_a',
        ],
        
        // Group 3: Part A - Physical burden self-perceived (身体的負担)
        3 => [
            'part' => 'A',
            'formula_type' => 'subtract_single',
            'base_value' => 5,
            'questions' => ['a.q7'],
            'result_key' => 'physical_burden_self_perceived',
            'result_group' => 'group_a',
        ],
        
        // Group 4: Part A - Workplace interpersonal stress (職場の対人関係のストレス)
        // Formula: 10−(No.12+No.13)+No.14
        4 => [
            'part' => 'A',
            'formula_type' => 'subtract_sum_add',
            'base_value' => 10,
            'questions' => ['a.q12', 'a.q13'],
            'add_question' => 'a.q14',
            'result_key' => 'workplace_interpersonal_stress',
            'result_group' => 'group_a',
        ],
        
        // Group 5: Part A - Workplace environment stress (職場環境によるストレス)
        // Formula: 5−No.15
        5 => [
            'part' => 'A',
            'formula_type' => 'subtract_single',
            'base_value' => 5,
            'questions' => ['a.q15'],
            'result_key' => 'workplace_environment_stress',
            'result_group' => 'group_a',
        ],
        
        // Group 6: Part A - Job control (仕事のコントロール度)
        // Formula: 15−(No.8+No.9+No.10)
        6 => [
            'part' => 'A',
            'formula_type' => 'subtract_sum',
            'base_value' => 15,
            'questions' => ['a.q8', 'a.q9', 'a.q10'],
            'result_key' => 'job_control',
            'result_group' => 'group_a',
        ],
        
        // Group 7: Part A - Skill utilization (技能の活用度)
        // Formula: No.11 (direct sum, not subtract)
        7 => [
            'part' => 'A',
            'formula_type' => 'direct_sum',
            'base_value' => 0,
            'questions' => ['a.q11'],
            'result_key' => 'skill_utilization',
            'result_group' => 'group_a',
        ],
        
        // Group 8: Part A - Job suitability (仕事への適性)
        8 => [
            'part' => 'A',
            'formula_type' => 'subtract_single',
            'base_value' => 5,
            'questions' => ['a.q16'],
            'result_key' => 'job_suitability',
            'result_group' => 'group_a',
        ],
        
        // Group 9: Part A - Work meaning (働きがい)
        9 => [
            'part' => 'A',
            'formula_type' => 'subtract_single',
            'base_value' => 5,
            'questions' => ['a.q17'],
            'result_key' => 'work_meaning',
            'result_group' => 'group_a',
        ],
        
        // Group 10: Part B - Vitality (活気)
        // Formula: No.1+No.2+No.3 (direct sum)
        10 => [
            'part' => 'B',
            'formula_type' => 'direct_sum',
            'base_value' => 0,
            'questions' => ['b.q1', 'b.q2', 'b.q3'],
            'result_key' => 'vitality',
            'result_group' => 'group_b',
        ],
        
        // Group 11: Part B - Irritability (イライラ感)
        // Formula: No.4+No.5+No.6 (direct sum)
        11 => [
            'part' => 'B',
            'formula_type' => 'direct_sum',
            'base_value' => 0,
            'questions' => ['b.q4', 'b.q5', 'b.q6'],
            'result_key' => 'irritability',
            'result_group' => 'group_b',
        ],
        
        // Group 12: Part B - Fatigue (疲労感)
        // Formula: No.7+No.8+No.9 (direct sum)
        12 => [
            'part' => 'B',
            'formula_type' => 'direct_sum',
            'base_value' => 0,
            'questions' => ['b.q7', 'b.q8', 'b.q9'],
            'result_key' => 'fatigue',
            'result_group' => 'group_b',
        ],
        
        // Group 13: Part B - Anxiety (不安感)
        // Formula: No.10+No.11+No.12 (direct sum)
        13 => [
            'part' => 'B',
            'formula_type' => 'direct_sum',
            'base_value' => 0,
            'questions' => ['b.q10', 'b.q11', 'b.q12'],
            'result_key' => 'anxiety',
            'result_group' => 'group_b',
        ],
        
        // Group 14: Part B - Depression (抑うつ感)
        // Formula: No.13〜No.18 の合計 (direct sum)
        14 => [
            'part' => 'B',
            'formula_type' => 'direct_sum',
            'base_value' => 0,
            'questions' => ['b.q13', 'b.q14', 'b.q15', 'b.q16', 'b.q17', 'b.q18'],
            'result_key' => 'depression',
            'result_group' => 'group_b',
        ],
        
        // Group 15: Part B - Physical symptoms (身体愁訴)
        // Formula: No.19〜No.29 の合計 (direct sum)
        15 => [
            'part' => 'B',
            'formula_type' => 'direct_sum',
            'base_value' => 0,
            'questions' => ['b.q19', 'b.q20', 'b.q21', 'b.q22', 'b.q23', 'b.q24', 'b.q25', 'b.q26', 'b.q27', 'b.q28', 'b.q29'],
            'result_key' => 'physical_symptoms',
            'result_group' => 'group_b',
        ],
        
        // Group 16: Part C - Supervisor support (上司からのサポート)
        // Formula: 15−(No.1+No.4) - Using c.q1 (No.1) and c.q4 (No.4)
        // Note: According to score.md, No.1 refers to c.q1, No.4 refers to c.q4
        16 => [
            'part' => 'C',
            'formula_type' => 'subtract_sum',
            'base_value' => 15,
            'questions' => ['c.q1', 'c.q4', 'c.q7'],
            'result_key' => 'supervisor_support',
            'result_group' => 'group_c',
        ],
        
        // Group 17: Part C - Coworker support (同僚からのサポート)
        // Formula: 15−(No.2+No.5) - Using c.q2 (No.2) and c.q5 (No.5)
        // Note: According to score.md, No.2 refers to c.q2, No.5 refers to c.q5
        17 => [
            'part' => 'C',
            'formula_type' => 'subtract_sum',
            'base_value' => 15,
            'questions' => ['c.q2', 'c.q5', 'c.q8'],
            'result_key' => 'coworker_support',
            'result_group' => 'group_c',
        ],
        
        // Group 18: Part C - Family/friend support (家族・友人からのサポート)
        // Formula: 15−(No.3+No.4) - Using c.q3 (No.3) and c.q4 (No.4)
        18 => [
            'part' => 'C',
            'formula_type' => 'subtract_sum',
            'base_value' => 15,
            'questions' => ['c.q3', 'c.q6', 'c.q9'],
            'result_key' => 'family_friend_support',
            'result_group' => 'group_c',
        ],
    ],
    
    /**
     * Conversion Table
     * Converts calculated values to scores (1-5) for each group
     * 
     * Structure:
     * - group_number: Group identifier (1-18)
     * - ranges: Array of range objects with min, max, and score
     *   Format: [['min' => 0, 'max' => 1, 'score' => 1], ...]
     * 
     * Note: Ranges should be ordered from lowest to highest.
     * The conversion will find the first range where min <= value <= max.
     */
    'conversion_table' => [
        // Group 1: Psychological workload quantity
        1 => [
            ['min' => 12, 'max' => 12, 'score' => 1],
            ['min' => 10, 'max' => 11, 'score' => 2],
            ['min' => 8, 'max' => 9, 'score' => 3],
            ['min' => 6, 'max' => 7, 'score' => 4],
            ['min' => 0, 'max' => 5, 'score' => 5],
        ],
        
        // Group 2: Psychological workload quality
        2 => [
            ['min' => 12, 'max' => 12, 'score' => 1],
            ['min' => 10, 'max' => 11, 'score' => 2],
            ['min' => 8, 'max' => 9, 'score' => 3],
            ['min' => 6, 'max' => 7, 'score' => 4],
            ['min' => 0, 'max' => 5, 'score' => 5],
        ],
        
        // Group 3: Physical burden (single question)
        3 => [
            ['min' => 4, 'max' => 4, 'score' => 1],
            ['min' => 3, 'max' => 3, 'score' => 2],
            ['min' => 2, 'max' => 2, 'score' => 3],
            ['min' => 1, 'max' => 1, 'score' => 4],
        ],
        
        // Group 4: Workplace interpersonal stress (10−(No.12+No.13)+No.14)
        4 => [
            ['min' => 10, 'max' => 12, 'score' => 1],
            ['min' => 8, 'max' => 9, 'score' => 2],
            ['min' => 6, 'max' => 7, 'score' => 3],
            ['min' => 4, 'max' => 5, 'score' => 4],
            ['min' => 0, 'max' => 3, 'score' => 5],
        ],
        
        // Group 5: Workplace environment stress (5−No.15, single question)
        5 => [
            ['min' => 4, 'max' => 4, 'score' => 1],
            ['min' => 3, 'max' => 3, 'score' => 2],
            ['min' => 2, 'max' => 2, 'score' => 3],
            ['min' => 0, 'max' => 1, 'score' => 4],
        ],
        
        // Group 6: Job control (15−(No.8+No.9+No.10))
        6 => [
            ['min' => 0, 'max' => 4, 'score' => 1],
            ['min' => 5, 'max' => 6, 'score' => 2],
            ['min' => 7, 'max' => 8, 'score' => 3],
            ['min' => 9, 'max' => 10, 'score' => 4],
            ['min' => 11, 'max' => 12, 'score' => 5],
        ],
        
        // Group 7: Skill utilization (No.11, direct sum)
        7 => [
            ['min' => 0, 'max' => 1, 'score' => 1],
            ['min' => 2, 'max' => 2, 'score' => 2],
            ['min' => 3, 'max' => 3, 'score' => 3],
            ['min' => 4, 'max' => 4, 'score' => 4],
        ],
        
        // Group 8: Job suitability (single question)
        8 => [
            ['min' => 0, 'max' => 1, 'score' => 1],
            ['min' => 2, 'max' => 2, 'score' => 2],
            ['min' => 3, 'max' => 3, 'score' => 3],
            ['min' => 4, 'max' => 4, 'score' => 5],
        ],
        
        // Group 9: Work meaning (single question)
        9 => [
            ['min' => 0, 'max' => 1, 'score' => 1],
            ['min' => 2, 'max' => 2, 'score' => 2],
            ['min' => 3, 'max' => 3, 'score' => 3],
            ['min' => 4, 'max' => 4, 'score' => 5],
        ],
        
        // Group 10: Vitality
        10 => [
            ['min' => 0, 'max' => 3, 'score' => 1],
            ['min' => 4, 'max' => 5, 'score' => 2],
            ['min' => 6, 'max' => 7, 'score' => 3],
            ['min' => 8, 'max' => 9, 'score' => 4],
            ['min' => 10, 'max' => 12, 'score' => 5],
        ],
        
        // Group 11: Irritability
        11 => [
            ['min' => 10, 'max' => 12, 'score' => 1],
            ['min' => 8, 'max' => 9, 'score' => 2],
            ['min' => 6, 'max' => 7, 'score' => 3],
            ['min' => 4, 'max' => 5, 'score' => 4],
            ['min' => 0, 'max' => 3, 'score' => 5],
        ],
        
        // Group 12: Fatigue
        12 => [
            ['min' => 11, 'max' => 12, 'score' => 1],
            ['min' => 8, 'max' => 10, 'score' => 2],
            ['min' => 5, 'max' => 7, 'score' => 3],
            ['min' => 4, 'max' => 4, 'score' => 4],
            ['min' => 0, 'max' => 3, 'score' => 5],
        ],
        
        // Group 13: Anxiety
        13 => [
            ['min' => 10, 'max' => 12, 'score' => 1],
            ['min' => 8, 'max' => 9, 'score' => 2],
            ['min' => 5, 'max' => 7, 'score' => 3],
            ['min' => 4, 'max' => 4, 'score' => 4],
            ['min' => 0, 'max' => 3, 'score' => 5],
        ],
        
        // Group 14: Depression
        14 => [
            ['min' => 17, 'max' => 24, 'score' => 1],
            ['min' => 13, 'max' => 16, 'score' => 2],
            ['min' => 9, 'max' => 12, 'score' => 3],
            ['min' => 7, 'max' => 8, 'score' => 4],
            ['min' => 0, 'max' => 6, 'score' => 5],
        ],
        
        // Group 15: Physical symptoms
        15 => [
            ['min' => 27, 'max' => 44, 'score' => 1],
            ['min' => 22, 'max' => 26, 'score' => 2],
            ['min' => 16, 'max' => 21, 'score' => 3],
            ['min' => 12, 'max' => 15, 'score' => 4],
            ['min' => 0, 'max' => 11, 'score' => 5],
        ],
        
        // Group 16: Supervisor support
        16 => [
            ['min' => 0, 'max' => 4, 'score' => 1],
            ['min' => 5, 'max' => 6, 'score' => 2],
            ['min' => 7, 'max' => 8, 'score' => 3],
            ['min' => 9, 'max' => 10, 'score' => 4],
            ['min' => 11, 'max' => 12, 'score' => 5],
        ],
        
        // Group 17: Coworker support
        17 => [
            ['min' => 0, 'max' => 5, 'score' => 1],
            ['min' => 6, 'max' => 7, 'score' => 2],
            ['min' => 8, 'max' => 9, 'score' => 3],
            ['min' => 10, 'max' => 11, 'score' => 4],
            ['min' => 12, 'max' => 12, 'score' => 5],
        ],
        
        // Group 18: Family/friend support
        18 => [
            ['min' => 0, 'max' => 6, 'score' => 1],
            ['min' => 7, 'max' => 8, 'score' => 2],
            ['min' => 9, 'max' => 9, 'score' => 3],
            ['min' => 10, 'max' => 11, 'score' => 4],
            ['min' => 12, 'max' => 12, 'score' => 5],
        ],

        // Part D, E, F, G, H: single-question or average, value 1-4 → score 1-4
        19 => [['min' => 1, 'max' => 1, 'score' => 1], ['min' => 2, 'max' => 2, 'score' => 2], ['min' => 3, 'max' => 3, 'score' => 3], ['min' => 4, 'max' => 4, 'score' => 4]],
        20 => [['min' => 1, 'max' => 1, 'score' => 1], ['min' => 2, 'max' => 2, 'score' => 2], ['min' => 3, 'max' => 3, 'score' => 3], ['min' => 4, 'max' => 4, 'score' => 4]],
        21 => [['min' => 1, 'max' => 1, 'score' => 1], ['min' => 2, 'max' => 2, 'score' => 2], ['min' => 3, 'max' => 3, 'score' => 3], ['min' => 4, 'max' => 4, 'score' => 4]],
        22 => [['min' => 1, 'max' => 1, 'score' => 1], ['min' => 2, 'max' => 2, 'score' => 2], ['min' => 3, 'max' => 3, 'score' => 3], ['min' => 4, 'max' => 4, 'score' => 4]],
        23 => [['min' => 1, 'max' => 1, 'score' => 1], ['min' => 2, 'max' => 2, 'score' => 2], ['min' => 3, 'max' => 3, 'score' => 3], ['min' => 4, 'max' => 4, 'score' => 4]],
        24 => [['min' => 1, 'max' => 1, 'score' => 1], ['min' => 2, 'max' => 2, 'score' => 2], ['min' => 3, 'max' => 3, 'score' => 3], ['min' => 4, 'max' => 4, 'score' => 4]],
        25 => [['min' => 1, 'max' => 1, 'score' => 1], ['min' => 2, 'max' => 2, 'score' => 2], ['min' => 3, 'max' => 3, 'score' => 3], ['min' => 4, 'max' => 4, 'score' => 4]],
        26 => [['min' => 1, 'max' => 1, 'score' => 1], ['min' => 2, 'max' => 2, 'score' => 2], ['min' => 3, 'max' => 3, 'score' => 3], ['min' => 4, 'max' => 4, 'score' => 4]],
        27 => [['min' => 1, 'max' => 1, 'score' => 1], ['min' => 2, 'max' => 2, 'score' => 2], ['min' => 3, 'max' => 3, 'score' => 3], ['min' => 4, 'max' => 4, 'score' => 4]],
        28 => [['min' => 1, 'max' => 1, 'score' => 1], ['min' => 2, 'max' => 2, 'score' => 2], ['min' => 3, 'max' => 3, 'score' => 3], ['min' => 4, 'max' => 4, 'score' => 4]],
        29 => [['min' => 1, 'max' => 1, 'score' => 1], ['min' => 2, 'max' => 2, 'score' => 2], ['min' => 3, 'max' => 3, 'score' => 3], ['min' => 4, 'max' => 4, 'score' => 4]],
        30 => [['min' => 1, 'max' => 1, 'score' => 1], ['min' => 2, 'max' => 2, 'score' => 2], ['min' => 3, 'max' => 3, 'score' => 3], ['min' => 4, 'max' => 4, 'score' => 4]],
        31 => [['min' => 1, 'max' => 1, 'score' => 1], ['min' => 2, 'max' => 2, 'score' => 2], ['min' => 3, 'max' => 3, 'score' => 3], ['min' => 4, 'max' => 4, 'score' => 4]],
        32 => [['min' => 1, 'max' => 1, 'score' => 1], ['min' => 2, 'max' => 2, 'score' => 2], ['min' => 3, 'max' => 3, 'score' => 3], ['min' => 4, 'max' => 4, 'score' => 4]],
        33 => [['min' => 1, 'max' => 1, 'score' => 1], ['min' => 2, 'max' => 2, 'score' => 2], ['min' => 3, 'max' => 3, 'score' => 3], ['min' => 4, 'max' => 4, 'score' => 4]],
        34 => [['min' => 1, 'max' => 1, 'score' => 1], ['min' => 2, 'max' => 2, 'score' => 2], ['min' => 3, 'max' => 3, 'score' => 3], ['min' => 4, 'max' => 4, 'score' => 4]],
        35 => [['min' => 1, 'max' => 1, 'score' => 1], ['min' => 2, 'max' => 2, 'score' => 2], ['min' => 3, 'max' => 3, 'score' => 3], ['min' => 4, 'max' => 4, 'score' => 4]],
        36 => [['min' => 1, 'max' => 1, 'score' => 1], ['min' => 2, 'max' => 2, 'score' => 2], ['min' => 3, 'max' => 3, 'score' => 3], ['min' => 4, 'max' => 4, 'score' => 4]],
        37 => [['min' => 1, 'max' => 1, 'score' => 1], ['min' => 2, 'max' => 2, 'score' => 2], ['min' => 3, 'max' => 3, 'score' => 3], ['min' => 4, 'max' => 4, 'score' => 4]],
        38 => [['min' => 1, 'max' => 1, 'score' => 1], ['min' => 2, 'max' => 2, 'score' => 2], ['min' => 3, 'max' => 3, 'score' => 3], ['min' => 4, 'max' => 4, 'score' => 4]],
        39 => [['min' => 1, 'max' => 1, 'score' => 1], ['min' => 2, 'max' => 2, 'score' => 2], ['min' => 3, 'max' => 3, 'score' => 3], ['min' => 4, 'max' => 4, 'score' => 4]],
        40 => [['min' => 1, 'max' => 1, 'score' => 1], ['min' => 2, 'max' => 2, 'score' => 2], ['min' => 3, 'max' => 3, 'score' => 3], ['min' => 4, 'max' => 4, 'score' => 4]],
        41 => [['min' => 1, 'max' => 1, 'score' => 1], ['min' => 2, 'max' => 2, 'score' => 2], ['min' => 3, 'max' => 3, 'score' => 3], ['min' => 4, 'max' => 4, 'score' => 4]],
        42 => [['min' => 1, 'max' => 1, 'score' => 1], ['min' => 2, 'max' => 2, 'score' => 2], ['min' => 3, 'max' => 3, 'score' => 3], ['min' => 4, 'max' => 4, 'score' => 4]],
    ],
    
    /**
     * Stress Level Classification Rules
     * 
     * User is assessed as high stress (has_stress) if meeting 1 of 2 conditions:
     * 
     * Condition 1: Total score of Part B <= 12
     * Condition 2: Total score of Part A + Part C <= 26 AND Total score of Part B <= 17
     * 
     * Note: min_score values are theoretical minimums for reference only (not used in evaluation).
     * They represent the lowest possible scores: Part B = 6 (1 x 6 groups), Part A+C = 12 (1 x 9 + 1 x 3 groups).
     */
    'stress_classification' => [
        'condition_1' => [
            'part' => 'B',
            'max_score' => 12,
            'min_score' => 6, // Reference only - not used in evaluation
        ],
        'condition_2' => [
            'parts' => ['A', 'C'],
            'max_score' => 26,
            'min_score' => 12, // Reference only - not used in evaluation
            'part_b_max_score' => 17,
        ],
    ],
];


