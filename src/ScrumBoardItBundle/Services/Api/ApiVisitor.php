<?php

namespace ScrumBoardItBundle\Services\Api;

use Symfony\Component\HttpFoundation\Request;
use ScrumBoardItBundle\Form\Type\Search\VisitorSearchType;
use ScrumBoardItBundle\Entity\Issue\Task;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Visitor service.
 *
 * @author Brieuc Pouliquen <brieuc.pouliquen@canaltp.fr>
 */
class ApiVisitor extends AbstractApi
{
    /**
     * @var array
     */
    private $issues;

    /**
     * Issue id incrementor.
     *
     * @var number
     */
    private $currentIssueIndex = 0;

    /**
     * Array of printed issues' ids.
     *
     * @var array
     */
    private $printedIssues;

    public function __construct(RequestStack $requestStack)
    {
        $session = $requestStack->getCurrentRequest()->getSession();
        $this->printedIssues = $session->get('printed_issues', []);
        $this->generateIssues();
    }

    /**
     * Generate an array of issues.
     */
    private function generateIssues()
    {
        $this->issues = array(
            1 => array(
                array(
                    'issue' => $this->createIssue(1, 'task', 'Appli SNDF', "Création d'itinéraire",
                        "EN TANT QU'utilisateur, JE SOUHAITERAIS pouvoir rechercher l'itinéraire le plus rapide AFIN DE pouvoir me diriger de façon optimale.",
                        5, null, true, false),
                    'sprint' => 1,
                ),
                array(
                    'issue' => $this->createIssue(4, 'subtask', 'Appli SNDF', "Récupérer un itinéraire de l'API Navitia",
                        "Hydrater la recherche utilisateur avec les données renvoyées par l'API de Navitia",
                        null, 14400, false, false),
                    'sprint' => 1,
                ),
                array(
                    'issue' => $this->createIssue(3, 'task', 'Appli SNDF', 'Recherche de gare',
                        "EN TANT QU'utilisateur, JE SOUHAITERAIS pouvoir obtenir des informations actualisée sur une gare AFIN DE pouvoir choisir judicieusement mes correspondances.",
                        5, null, true, false),
                    'sprint' => 1,
                ),
                array(
                    'issue' => $this->createIssue(2, 'subtask', 'Appli SNDF', "Contacter l'API Navitia",
                        "Permettre une transmission fonctionnelle entre l'application et l'API Navitia",
                        null, 115200, false, false),
                    'sprint' => 1,
                ),
            ),
            2 => array(
                array(
                    'issue' => $this->createIssue(1, 'subtask', 'Site Thabès', "Service d'authentification",
                        "Création d'un service d'authentification par identifiant et mot de passe",
                        null, 57600, false, false),
                    'sprint' => 1,
                ),
                array(
                    'issue' => $this->createIssue(2, 'task', 'Site Thabès', 'Sécurité',
                        "EN TANT QU'administrateur du site, JE SOUHAITERAIS une répartition des droits de lecture et d'écriture sur les données du site AFIN DE protéger ces données.",
                        13, null, true, false),
                    'sprint' => 1,
                ),
                array(
                    'issue' => $this->createIssue(4, 'subtask', 'Site Thabès', "Comptes d'utilisateurs",
                        "Création d'un compte administrateur et de 10 comptes collaborateurs",
                        null, 28800, false, false),
                    'sprint' => 1,
                ),
                array(
                    'issue' => $this->createIssue(5, 'subtask', 'Site Thabès', 'Protection des données',
                        "Cryptage des données du serveur et des tokens d'authentification",
                        null, 28800, false, false),
                    'sprint' => 1,
                ),
                array(
                    'issue' => $this->createIssue(6, 'task', 'Site Thabès', "Page d'accueil",
                        "Page d'accueil dynamique (animations)",
                        null, 115200, false, true),
                    'sprint' => 1,
                ),
                array(
                    'issue' => $this->createIssue(7, 'task', 'Site Thabès', "Logo de l'entreprise",
                        "EN TANT QUE directeur de l'entreprise, J'AIMERAIS que le logo de mon entreprise soit visible dans l'en-tête du site AFIN DE rendre le site plus identifiable.",
                        3, null, true, false),
                    'sprint' => 1,
                ),
                array(
                    'issue' => $this->createIssue(9, 'task', 'Site Thabès', 'Champ de recherche',
                        "Proposer des choix pertinents à l'utlisateur quand celui-ci tape dans la barre de recherche",
                        null, 115200, false, true),
                    'sprint' => 2,
                ),
                array(
                    'issue' => $this->createIssue(8, 'task', 'Site Thabès', 'Formulaire de contact',
                        "EN TANT QU'utilisateur, J'AIMERAIS pouvoir envoyer un mail par le site à l'entreprise Thabès AFIN DE pouvoir contacter l'entreprise Thabès simplement",
                        8, null, true, false),
                    'sprint' => 2,
                ),
            ),
        );
    }

    /**
     * Create an issue.
     *
     * @param string $type
     * @param string $title
     * @param string $description
     * @param number $complexity
     * @param number $timeBox
     * @param bool   $isUS
     * @param bool   $isProofOfConcept
     *
     * @return Task
     */
    private function createIssue($number, $type, $project, $title, $description, $complexity, $timeBox, $isUS, $isProofOfConcept)
    {
        $task = new Task();
        $task->setId(++$this->currentIssueIndex);
        $task->setNumber($number);
        $task->setType($type);
        $task->setProject($project);
        $task->setTitle($title);
        $task->setDescription($description);
        $task->setComplexity($complexity);
        $task->setTimeBox(round($timeBox / 3600, 0).' h');
        $task->setUserStory($isUS);
        $task->setProofOfConcept($isProofOfConcept);
        $task->setPrinted(in_array($task->getId(), $this->printedIssues));

        return $task;
    }

    /**
     * {@inheritdoc}
     */
    public function getFormType()
    {
        return VisitorSearchType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getProjects()
    {
        return array(
            'Appli SNDF' => 1,
            'Site Thabès' => 2,
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getSprints($project)
    {
        if (!empty($project)) {
            $sprints = array(
                1 => array(
                    'Actif' => array(
                        'Sprint 1' => 1,
                    ),
                ),
                2 => array(
                    'Actif' => array(
                        'Sprint 1' => 1,
                    ),
                    'Futurs' => array(
                        'Sprint 2' => 2,
                    ),
                ),
            );

            return $sprints[$project];
        }

        return;
    }

    /**
     * {@inheritdoc}
     */
    public function searchIssues($searchFilters = null)
    {
        if (!empty($searchFilters['project'])) {
            $search = $this->issues[$searchFilters['project']];
            $issues = array();
            if (!empty($searchFilters['sprint'])) {
                foreach ($search as $issue) {
                    if ($issue['sprint'] == $searchFilters['sprint']) {
                        $issues[] = $issue['issue'];
                    }
                }
            } else {
                foreach ($search as $issue) {
                    $issues[] = $issue['issue'];
                }
            }

            return $issues;
        }

        return;
    }

    /**
     * {@inheritdoc}
     */
    public function getSelectedIssues(Request $request, $selected)
    {
        $filters = $request->getSession()->get('filters');
        $issues = array();
        if (empty($selected)) {
            $issues = $this->searchIssues($filters);
        } else {
            foreach ($this->issues[$filters['project']] as $issue) {
                if (in_array($issue['issue']->getId(), $selected)) {
                    $issues[$issue['issue']->getId()] = $issue['issue'];
                }
            }
        }

        return $issues;
    }

    /**
     * {@inheritdoc}
     */
    public function getSearchFilters(Request $request)
    {
        $session = $request->getSession();
        if (!$session->has('filters')) {
            $this->initFilters($session);
        }

        if (!$session->has('printed_issues')) {
            $session->set('printed_issues', array());
        }

        $searchFilters = $request->get('visitor_search');
        $searchFilters['projects'] = $this->getProjects();
        if (empty($searchFilters['project'])) {
            $searchFilters['project'] = null;
        }
        $searchFilters['sprints'] = $this->getSprints($searchFilters['project']);
        if (empty($searchFilters['sprint'])) {
            $searchFilters['sprint'] = null;
        }

        $session->set('filters', array(
            'project' => $searchFilters['project'],
            'sprint' => $searchFilters['sprint'],
        ));

        return $searchFilters;
    }

    /**
     * {@inheritdoc}
     */
    public function addFlag(Request $request, $selected)
    {
        if (!empty($selected)) {
            $session = $request->getSession();
            $issues = $this->getSelectedIssues($request, $selected);
            $printedIssues = $session->get('printed_issues');
            foreach ($issues as $issue) {
                $printedIssues[$issue->getId()] = $issue->getId();
            }
            $session->set('printed_issues', $printedIssues);
        }
    }
}
