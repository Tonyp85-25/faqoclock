<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Repository\QuestionRepository;
use App\Entity\Question;
use App\Form\AnswerType;
use App\Entity\Answer;
use App\Repository\AnswerRepository;
use Symfony\Component\HttpFoundation\Request;

class MainController extends Controller
{
    /**
     * @Route("/", name="home", methods="GET")
     */
    public function home(QuestionRepository $questionRepository, Request $request)
   
    {
        $questions = $questionRepository->sortQuestionsByDate();
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $questions,                                /* Query */
            $request->query->getInt('page', 1),     /* page number */
            $request->query->getInt('limit', 7)     /* limit per page */
        );

        return $this->render('main/index.html.twig', [
            'questions' => $result,
        ]);
    }
     /**
     * @Route("/question/{id}", name="question_show", methods="GET", requirements= {"id": "\d+"} )
     */
    public function show(Question $question,  Request $request, AnswerRepository $repo)
    {
        //On utilise la méthode personnalisée pour récupérer les réponses d'une question aec la préférée en premier
        $answers = $repo->findAnsByQuestion($question);
      
       
        return $this->render('question/show.html.twig', [
            'question' => $question,
            'answers' => $answers,
           
        ]);
    }

    /**
     * @Route("/cgu", name="cgu", methods="GET")
     */
    public function cgu(){
        return $this->render('main/cgu.html.twig');
    }
}
