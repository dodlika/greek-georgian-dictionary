<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GrammarController extends Controller
{
    private $grammarSections = [
        'alphabet' => 'Greek Alphabet',
        'articles' => 'Articles',
        'nouns' => 'Nouns & Cases',
        'adjectives' => 'Adjectives',
        'pronouns' => 'Pronouns',
        'verbs' => 'Verbs & Conjugation',
        'tenses' => 'Verb Tenses',
        'prepositions' => 'Prepositions',
        'numbers' => 'Numbers',
        'syntax' => 'Syntax & Word Order',
        'expressions' => 'Common Expressions'
    ];

    public function index()
    {
        return view('grammar.index', [
            'sections' => $this->grammarSections
        ]);
    }

    public function show($section)
    {
        if (!array_key_exists($section, $this->grammarSections)) {
            abort(404);
        }

        $grammarData = $this->getGrammarData($section);
        
        return view('grammar.show', [
            'section' => $section,
            'sectionTitle' => $this->grammarSections[$section],
            'sections' => $this->grammarSections,
            'data' => $grammarData
        ]);
    }

    private function getGrammarData($section)
    {
        switch ($section) {
            case 'alphabet':
                return $this->getAlphabetData();
            case 'articles':
                return $this->getArticlesData();
            case 'nouns':
                return $this->getNounsData();
            case 'adjectives':
                return $this->getAdjectivesData();
            case 'pronouns':
                return $this->getPronounsData();
            case 'verbs':
                return $this->getVerbsData();
            case 'tenses':
                return $this->getTensesData();
            case 'prepositions':
                return $this->getPrepositionsData();
            case 'numbers':
                return $this->getNumbersData();
            case 'syntax':
                return $this->getSyntaxData();
            case 'expressions':
                return $this->getExpressionsData();
            default:
                return [];
        }
    }

    private function getAlphabetData()
    {
        return [
            'letters' => [
                ['upper' => 'Α', 'lower' => 'α', 'name' => 'άλφα', 'sound' => 'a'],
                ['upper' => 'Β', 'lower' => 'β', 'name' => 'βήτα', 'sound' => 'v'],
                ['upper' => 'Γ', 'lower' => 'γ', 'name' => 'γάμμα', 'sound' => 'g/y'],
                ['upper' => 'Δ', 'lower' => 'δ', 'name' => 'δέλτα', 'sound' => 'th (voiced)'],
                ['upper' => 'Ε', 'lower' => 'ε', 'name' => 'έψιλον', 'sound' => 'e'],
                ['upper' => 'Ζ', 'lower' => 'ζ', 'name' => 'ζήτα', 'sound' => 'z'],
                ['upper' => 'Η', 'lower' => 'η', 'name' => 'ήτα', 'sound' => 'i'],
                ['upper' => 'Θ', 'lower' => 'θ', 'name' => 'θήτα', 'sound' => 'th (voiceless)'],
                ['upper' => 'Ι', 'lower' => 'ι', 'name' => 'γιώτα', 'sound' => 'i'],
                ['upper' => 'Κ', 'lower' => 'κ', 'name' => 'κάππα', 'sound' => 'k'],
                ['upper' => 'Λ', 'lower' => 'λ', 'name' => 'λάμδα', 'sound' => 'l'],
                ['upper' => 'Μ', 'lower' => 'μ', 'name' => 'μι', 'sound' => 'm'],
                ['upper' => 'Ν', 'lower' => 'ν', 'name' => 'νι', 'sound' => 'n'],
                ['upper' => 'Ξ', 'lower' => 'ξ', 'name' => 'ξι', 'sound' => 'ks'],
                ['upper' => 'Ο', 'lower' => 'ο', 'name' => 'όμικρον', 'sound' => 'o'],
                ['upper' => 'Π', 'lower' => 'π', 'name' => 'πι', 'sound' => 'p'],
                ['upper' => 'Ρ', 'lower' => 'ρ', 'name' => 'ρο', 'sound' => 'r'],
                ['upper' => 'Σ', 'lower' => 'σ/ς', 'name' => 'σίγμα', 'sound' => 's'],
                ['upper' => 'Τ', 'lower' => 'τ', 'name' => 'ταυ', 'sound' => 't'],
                ['upper' => 'Υ', 'lower' => 'υ', 'name' => 'ύψιλον', 'sound' => 'i'],
                ['upper' => 'Φ', 'lower' => 'φ', 'name' => 'φι', 'sound' => 'f'],
                ['upper' => 'Χ', 'lower' => 'χ', 'name' => 'χι', 'sound' => 'kh'],
                ['upper' => 'Ψ', 'lower' => 'ψ', 'name' => 'ψι', 'sound' => 'ps'],
                ['upper' => 'Ω', 'lower' => 'ω', 'name' => 'ωμέγα', 'sound' => 'o'],
            ],
            'accents' => [
                'acute' => ['symbol' => '´', 'example' => 'άνθρωπος', 'description' => 'Main stress accent'],
                'circumflex' => ['symbol' => '῀', 'example' => 'σῶμα', 'description' => 'Used in some cases'],
                'grave' => ['symbol' => '`', 'example' => 'καὶ', 'description' => 'Rare, mostly historical']
            ]
        ];
    }

    private function getArticlesData()
    {
        return [
            'definite' => [
                'masculine' => [
                    'nom_sg' => 'ο', 'gen_sg' => 'του', 'acc_sg' => 'τον',
                    'nom_pl' => 'οι', 'gen_pl' => 'των', 'acc_pl' => 'τους'
                ],
                'feminine' => [
                    'nom_sg' => 'η', 'gen_sg' => 'της', 'acc_sg' => 'την',
                    'nom_pl' => 'οι', 'gen_pl' => 'των', 'acc_pl' => 'τις'
                ],
                'neuter' => [
                    'nom_sg' => 'το', 'gen_sg' => 'του', 'acc_sg' => 'το',
                    'nom_pl' => 'τα', 'gen_pl' => 'των', 'acc_pl' => 'τα'
                ]
            ],
            'indefinite' => [
                'masculine' => ['nom' => 'ένας', 'gen' => 'ενός', 'acc' => 'έναν'],
                'feminine' => ['nom' => 'μία', 'gen' => 'μίας', 'acc' => 'μία'],
                'neuter' => ['nom' => 'ένα', 'gen' => 'ενός', 'acc' => 'ένα']
            ]
        ];
    }

    private function getNounsData()
    {
        return [
            'cases' => [
                'nominative' => ['function' => 'Subject', 'question' => 'ποιος/τι;'],
                'genitive' => ['function' => 'Possession', 'question' => 'ποιανού/τίνος;'],
                'accusative' => ['function' => 'Direct object', 'question' => 'ποιον/τι;'],
                'vocative' => ['function' => 'Address', 'question' => 'Call someone']
            ],
            'declensions' => [
                'masculine_o' => [
                    'example' => 'άνθρωπος',
                    'singular' => ['άνθρωπος', 'ανθρώπου', 'άνθρωπο', 'άνθρωπε'],
                    'plural' => ['άνθρωποι', 'ανθρώπων', 'ανθρώπους', 'άνθρωποι']
                ],
                'feminine_i' => [
                    'example' => 'γυναίκα',
                    'singular' => ['γυναίκα', 'γυναίκας', 'γυναίκα', 'γυναίκα'],
                    'plural' => ['γυναίκες', 'γυναικών', 'γυναίκες', 'γυναίκες']
                ],
                'neuter_o' => [
                    'example' => 'παιδί',
                    'singular' => ['παιδί', 'παιδιού', 'παιδί', 'παιδί'],
                    'plural' => ['παιδιά', 'παιδιών', 'παιδιά', 'παιδιά']
                ]
            ]
        ];
    }

    private function getAdjectivesData()
    {
        return [
            'agreement' => 'Adjectives must agree with nouns in gender, number, and case',
            'example' => [
                'masculine' => 'καλός άνθρωπος',
                'feminine' => 'καλή γυναίκα',
                'neuter' => 'καλό παιδί'
            ],
            'comparison' => [
                'positive' => 'καλός (good)',
                'comparative' => 'καλύτερος (better)',
                'superlative' => 'ο καλύτερος (the best)'
            ]
        ];
    }

    private function getPronounsData()
    {
        return [
            'personal' => [
                'first_person' => ['εγώ', 'εμείς'],
                'second_person' => ['εσύ', 'εσείς'],
                'third_person' => ['αυτός/αυτή/αυτό', 'αυτοί/αυτές/αυτά']
            ],
            'possessive' => [
                'my' => 'μου',
                'your' => 'σου',
                'his/her/its' => 'του/της/του',
                'our' => 'μας',
                'your_pl' => 'σας',
                'their' => 'τους'
            ]
        ];
    }

    private function getVerbsData()
    {
        return [
            'conjugation_groups' => [
                'group_1' => 'Verbs ending in -ω (most common)',
                'group_2' => 'Verbs ending in -άω, -ώ'
            ],
            'example_present' => [
                'verb' => 'γράφω (to write)',
                'conjugation' => [
                    'εγώ γράφω',
                    'εσύ γράφεις',
                    'αυτός γράφει',
                    'εμείς γράφουμε',
                    'εσείς γράφετε',
                    'αυτοί γράφουν'
                ]
            ],
            'irregular_verbs' => [
                'είμαι' => 'to be',
                'έχω' => 'to have',
                'πάω' => 'to go',
                'έρχομαι' => 'to come'
            ]
        ];
    }

    private function getTensesData()
    {
        return [
            'present' => ['γράφω', 'I write/I am writing'],
            'imperfect' => ['έγραφα', 'I was writing'],
            'aorist' => ['έγραψα', 'I wrote'],
            'perfect' => ['έχω γράψει', 'I have written'],
            'pluperfect' => ['είχα γράψει', 'I had written'],
            'future' => ['θα γράψω', 'I will write'],
            'future_continuous' => ['θα γράφω', 'I will be writing']
        ];
    }

    private function getPrepositionsData()
    {
        return [
            'common_prepositions' => [
                'σε' => 'in, to, at (+accusative)',
                'από' => 'from (+accusative)',
                'με' => 'with (+accusative)',
                'για' => 'for, about (+accusative)',
                'χωρίς' => 'without (+accusative)',
                'μετά' => 'after (+accusative)',
                'πριν' => 'before (+accusative)',
                'κατά' => 'during, according to (+accusative)'
            ]
        ];
    }

    private function getNumbersData()
    {
        return [
            'cardinal' => [
                '1' => 'ένας/μία/ένα',
                '2' => 'δύο',
                '3' => 'τρεις/τρία',
                '4' => 'τέσσερις/τέσσερα',
                '5' => 'πέντε',
                '6' => 'έξι',
                '7' => 'επτά',
                '8' => 'οκτώ',
                '9' => 'εννιά',
                '10' => 'δέκα'
            ],
            'ordinal' => [
                '1st' => 'πρώτος',
                '2nd' => 'δεύτερος',
                '3rd' => 'τρίτος',
                '4th' => 'τέταρτος',
                '5th' => 'πέμπτος'
            ]
        ];
    }

    private function getSyntaxData()
    {
        return [
            'word_order' => 'Greek has flexible word order, but SVO (Subject-Verb-Object) is most common',
            'examples' => [
                'basic' => 'Ο Γιάννης διαβάζει το βιβλίο',
                'emphasis' => 'Το βιβλίο διαβάζει ο Γιάννης (The book, John is reading)'
            ],
            'questions' => [
                'yes_no' => 'Formed with rising intonation or μήπως',
                'wh_questions' => 'ποιος (who), τι (what), πού (where), πότε (when), πώς (how), γιατί (why)'
            ]
        ];
    }

    private function getExpressionsData()
    {
        return [
            'greetings' => [
                'Γεια σου' => 'Hello (informal)',
                'Γεια σας' => 'Hello (formal)',
                'Καλημέρα' => 'Good morning',
                'Καλησπέρα' => 'Good evening',
                'Καληνύχτα' => 'Good night'
            ],
            'polite_phrases' => [
                'Παρακαλώ' => 'Please/You\'re welcome',
                'Ευχαριστώ' => 'Thank you',
                'Συγγνώμη' => 'Excuse me/Sorry',
                'Με συγχωρείτε' => 'Pardon me (formal)'
            ],
            'useful_expressions' => [
                'Πώς σε λένε;' => 'What is your name?',
                'Μιλάτε αγγλικά;' => 'Do you speak English?',
                'Δεν καταλαβαίνω' => 'I don\'t understand',
                'Μπορείτε να με βοηθήσετε;' => 'Can you help me?'
            ]
        ];
    }
}