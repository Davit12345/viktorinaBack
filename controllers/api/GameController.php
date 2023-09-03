<?php


namespace app\controllers\api;


use app\models\Answers;
use app\models\Categories;
use app\models\Friends;
use app\models\Questions;
use app\models\Users;
use Yii;
use yii\db\Query;

class GameController extends ApiBaseController
{
    public function actionGetData()
    {
        $categories = \Yii::$app->request->post('categories');

//        $questionsWithAnswers = Questions::find()
//            ->with([
//                'answers' => function ($query) {
//                    $query->select('text'); // Specify the columns you need from the answers table
//                }
//            ])
//            ->all();

//        $query = (new Query())
//            ->select(['questions.*', 'answers.text']) // Select the columns you need
//            ->from('questions') // Replace with your actual table name
//            ->leftJoin('answers', 'questions.id = answers.question_id')
//            ->all();


//        $questionsWithAnswers = Questions::find()
//            ->with('answers') // This fetches the associated answers
//            ->all();

        $questionsWithAnswers = Questions::find()
            ->with('answers') // This fetches the associated answers
            ->orderBy('RAND()') // This will randomize the order
            ->limit(10)
            ->all();

// Organize the data into the desired format
        $organizedData = [];
        foreach ($questionsWithAnswers as $question) {
            $organizedQuestion = [
                'id' => $question->id,
                'question' => $question->question,
                'answers' => [],
            ];

            foreach ($question->answers as $answer) {
                $organizedAnswer = [
                    'question_id' => $answer->question_id,
                    'text' => $answer->text,
                    'flag' => $answer->flag,
                ];
                $organizedQuestion['answers'][] = $organizedAnswer;
            }

            $organizedData[] = $organizedQuestion;
        }
            return $this->createResponse($organizedData);

        }


}