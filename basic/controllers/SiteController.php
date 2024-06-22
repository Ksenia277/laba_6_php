<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\RegistrForm;
use app\models\ContactForm;
use app\models\Bot;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException; 
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;
use app\models\Question;
use app\models\Answer;
use app\models\Message;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'index', 'create', 'createAnswer'], 
                'rules' => [
                    [
                        'actions' => ['logout', 'index', 'create', 'createAnswer'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionCreate()
    {
        if (Yii::$app->user->isGuest) {
            throw new ForbiddenHttpException('Вы должны быть авторизованы, чтобы создать вопрос.');
        }
    
        $model = new Question();
        return $this->render('create', ['model' => $model]);
    }

    public function actionHome()
    {
        return $this->render('home');
    }
    

    public function actionViewBot($id)
    {
        $bot = Bot::findOne($id);
        if ($bot === null) {
            throw new NotFoundHttpException('Бот не найден.');
        }

        return $this->render('view-bot', [
            'bot' => $bot,
        ]);
    }

    public function actionCreateBot()
    {
        $bot = new Bot();
        if ($bot->load(Yii::$app->request->post())) {
            $bot->creator_id = Yii::$app->user->id; 
            if ($bot->save()) {
                Yii::$app->session->setFlash('success', 'Бот успешно создан!');
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка создания бота: ' . implode(', ', $bot->errors));
            }
        }

        return $this->render('create-bot', [
            'bot' => $bot,
        ]);
    }

    public function actionView($id = null)
    {
        if ($id === null) {
            throw new BadRequestHttpException('Missing required parameter: id');
        }
    
        $bot = Bot::findOne($id);
        if ($bot === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    
        return $this->render('view', [
            'bot' => $bot,
        ]);
    }

    public function actionCreateAnswer()
    {
        if(Yii::$app->user->identity->role === 2){

            $questions = Question::find()->all();
            $model = new Answer();

            if ($model->load(Yii::$app->request->post())) {
                $question_id = Yii::$app->request->post('question_id');
                $model->question_id = $question_id;
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Ответ успешно создан');
                } else {
                    Yii::$app->session->setFlash('error', 'Ошибка создания ответа');
                }
            }

            return $this->render('create-answer', [
                'model' => $model, 
                'questions' => $questions,
            ]);
        }
        return $this->redirect(['home']);
    }

    public function actionDeleteBot($id)
    {
        $bot = Bot::findOne($id);
        if ($bot === null) {
            throw new NotFoundHttpException('Бот не найден.');
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            Message::deleteAll(['bot_id' => $bot->id]);
            if ($bot->delete()) {
                $transaction->commit();
                Yii::$app->session->setFlash('success', 'Бот удален успешно!');
                return $this->redirect(['index']);
            } else {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Ошибка удаления бота: '. implode(', ', $bot->errors));
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    public function actionCreateQuestion()
    {   
        if(Yii::$app->user->identity->role === 2){

        $bots = Bot::find()->all();
        $question = new Question();
        if ($question->load(Yii::$app->request->post())) {
            $question->bot_id = Yii::$app->request->post('Question')['bot_id'];
            if ($question->save()) {
                Yii::$app->session->setFlash('success', 'Вопрос успешно создан!');
                return $this->redirect(['view-bot', 'id' => $question->bot_id]);
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка создания вопроса: '. implode(', ', $question->errors));
            }
        }
        
        return $this->render('create-question', [
            'bots' => $bots,
            'question' => $question,
        ]);
    }
    return $this->redirect(['home']);
    }

    public function actionChat($bot_id)
    {
        if(Yii::$app->user->identity->role === 1){

            $bot = Bot::findOne($bot_id);
            if (!$bot) {
                throw new NotFoundHttpException('Бот не найден');
            }

            $messages = Message::find()->where(['bot_id' => $bot_id, 'user_id' => Yii::$app->user->id])->all();

            $questions = Message::find()->where(['bot_id' => $bot_id, 'type' => 'вопрос'])->all();
            $answers = Message::find()->where(['bot_id' => $bot_id, 'type' => 'ответ'])->all();

            $message = new Message();

            if (Yii::$app->request->post()) {
                $message->load(Yii::$app->request->post());
                $message->bot_id = $bot_id;
                $message->user_id = Yii::$app->user->id;
                $message->created_at = time();
                $message->type = 'вопрос'; 
                if (!$message->save()) {
                    Yii::$app->session->setFlash('error', 'Ошибка отправки сообщения: '. implode(', ', array_map(function($error) {
                        return $error[0];
                    }, $message->errors)));
                } else {
                    $response = $this->getResponseFromDatabase($message->text);
                    if ($response) {
                        $answer = new Message();
                        $answer->bot_id = $bot_id;
                        $answer->user_id = Yii::$app->user->id;
                        $answer->text = $response;
                        $answer->type = 'ответ'; 
                        $answer->save();
                    } else {
                        $answer = new Message();
                        $answer->bot_id = $bot_id;
                        $answer->user_id = Yii::$app->user->id;
                        $answer->text = 'Бот не знает ответа на этот вопрос';
                        $answer->type = 'ответ'; 
                        $answer->save();
                    }
                }
            }

            return $this->render('chat', [
                'bot' => $bot,
                'essages' => $messages,
                'questions' => $questions,
                'answers' => $answers,
                'messages' => $messages,
                'model' => $message, 
            ]);

        }
        return $this->redirect(['home']);
    }

    private function getResponseFromDatabase($question_text)
    {
        $question = Question::findOne(['text' => $question_text]);
        if (!$question) {
            return null; 
        }

        $answer = Answer::findOne(['question_id' => $question->id]);
        if (!$answer) {
            return null; 
        }

        return $answer->text; 
    }

    public function actionChatBots()
    {
        $bots = Bot::find()->all(); 

        return $this->render('chat-bots', [
            'bots' => $bots,
        ]);
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        if(Yii::$app->user->identity->role === 2){

            $bots = Bot::find()->all();
            return $this->render('index', [
                'bots' => $bots,
            ]);
        }
        return $this->redirect(['home']);
    }
    


    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionRegister()
    {
        $model = new RegistrForm();
        if ($model->load(Yii::$app->request->post()) && $model->register()) {
            return $this->goHome();
        }
        
        return $this->render('registr', [
            'model' => $model,
        ]);
    }
}
