<?php

namespace Tests\Console\App\Service\Transformer;

use Console\App\Service\Transformer\GithubResultToModelTransformer;
use PHPUnit\Framework\TestCase;

class GithubResultToModelTransformerTest extends TestCase
{
    /**
     * @dataProvider getTransformContributionsToModelContent
     */
    public function testTransformContributionsToModelAndGetTotalReviews(array $content, array $expectedResults): void
    {
        $transformer = new GithubResultToModelTransformer();

        $reviewContributionsByOrganization = $transformer->transformContributionsToModel('joeylelievre', $content);

        $insiders = [
            'matks',
            'Progi1984',
            'sowbiba',
        ];
        $this->assertSame($reviewContributionsByOrganization->getTotalReviews($insiders), $expectedResults['totalReviews']);
        $this->assertSame($reviewContributionsByOrganization->getReviewsByDate(), $expectedResults['reviewsByDate']);
    }

    public function getTransformContributionsToModelContent(): \Generator
    {
        yield [
            [
                [
                    'contributions' => [
                        'totalCount' => 9,
                        'nodes' => [
                            [
                                'occurredAt' => '2021-03-11T10:29:58Z',
                                'pullRequestReview' => [
                                    'state' => 'COMMENTED',
                                    'pullRequest' => [
                                        'author' => [
                                            'login' => 'matks',
                                        ],
                                    ],
                                ],
                            ],
                            [
                                'occurredAt' => '2021-03-10T14:18:13Z',
                                'pullRequestReview' => [
                                    'state' => 'APPROVED',
                                    'pullRequest' => [
                                        'author' => [
                                            'login' => 'ks129',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'repository' => [
                        'name' => 'phpstan-prestashop',
                    ],
                ],
                [
                    'contributions' => [
                        'totalCount' => 9,
                        'nodes' => [
                            [
                                'occurredAt' => '2021-03-10T16:25:19Z',
                                'pullRequestReview' => [
                                    'state' => 'CHANGES_REQUESTED',
                                    'pullRequest' => [
                                        'author' => [
                                            'login' => 'JevgenijVisockij',
                                        ],
                                    ],
                                ],
                            ],
                            [
                                'occurredAt' => '2021-03-08T08:55:53Z',
                                'pullRequestReview' => [
                                    'state' => 'DISMISSED',
                                    'pullRequest' => [
                                        'author' => [
                                            'login' => 'sowbiba',
                                        ],
                                    ],
                                ],
                            ],
                            [
                                'occurredAt' => '2021-03-05T17:29:58Z',
                                'pullRequestReview' => [
                                    'state' => 'PENDING',
                                    'pullRequest' => [
                                        'author' => [
                                            'login' => 'Progi1984',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'repository' => [
                        'name' => 'docs',
                    ],
                ],
            ],
            [
                'totalReviews' => [
                    'ALL' => 4,
                    'COMMENTED' => 1,
                    'APPROVED' => 1,
                    'CHANGES_REQUESTED' => 1,
                    'DISMISSED' => 1,
                    'INSIDE' => 2,
                    'OUTSIDE' => 2,
                ],
                'reviewsByDate' => [
                    '2021-03-11' => [
                        'joeylelievre' => 1,
                    ],
                    '2021-03-10' => [
                        'joeylelievre' => 2,
                    ],
                    '2021-03-08' => [
                        'joeylelievre' => 1,
                    ],
                ],
            ],
        ];

        yield [
            [],
            [
                'totalReviews' => [
                    'ALL' => 0,
                    'COMMENTED' => 0,
                    'APPROVED' => 0,
                    'CHANGES_REQUESTED' => 0,
                    'DISMISSED' => 0,
                    'INSIDE' => 0,
                    'OUTSIDE' => 0,
                ],
                'reviewsByDate' => [],
            ],
        ];
    }
}
