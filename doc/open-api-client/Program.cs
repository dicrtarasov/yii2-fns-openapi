using System;
using System.Collections.Generic;
using System.Diagnostics;
using System.Linq;
using System.Runtime.CompilerServices;
using System.ServiceModel;
using System.ServiceModel.Channels;
using System.Text;
using System.Threading;
using System.Threading.Tasks;
using System.Xml;

namespace open_api_client
{
    class Program
    {
        static void Main(string[] args)
        {
            OpenApiMessageConsumerServiceClientExample();

            OpenApiAsyncMessageConsumerServiceClientExample();

            Console.Read();
        }

        // Пример использования синхронного сервиса сообщений открытых API на примере сервиса аутентификации.
        // Пример полностью работоспособный.
        // К проекту прикреплен файл OpenApiMessageConsumerService-v0-1-soapui-project.xml, 
        // который можно ипортировать в SoapUI и запустить OpenApiMessageConsumerServiceSoapBinding MockService.
        // Для того чтобы MockService не подвис (глюк SoapUI), рекомендуется его запустить, остановить и снова запустить.
        static void OpenApiMessageConsumerServiceClientExample()
        {
            // Создание клиента
            OpenApiMessageConsumerServiceReference.OpenApiMessageConsumerServicePortTypeClient client =
                new OpenApiMessageConsumerServiceReference.OpenApiMessageConsumerServicePortTypeClient();

            // Создание запроса
            OpenApiMessageConsumerServiceReference.GetMessageRequest getMessageRequest = new OpenApiMessageConsumerServiceReference.GetMessageRequest();
            // В поле Message, необходимо добавить сообщение в соответсвии с элементом AuthRequest, описанным в AuthService-types-v0.1.xsd
            // Причем, в обязательном порядке должно быть указано пространство имен, и все элементы должны быть сформированы с учетом данного пространства имен
            // Как вариант сгенерировать типы на основе xsd схемы, и сериализовать их в xml
            XmlDocument request = new XmlDocument();
            // Данный фрагмент Xml приведен для примера и не содержит полный Xml соответсвующий схеме
            request.LoadXml("<tns:AuthRequest xmlns:tns=\"urn://x-artefacts-gnivc-ru/ais3/kkt/AuthService/types/1.0\">" +
                                "<tns:AuthAppInfo>" +
                                    "<tns:MasterToken>MASTER_TOKEN_ISSUED_BY_FNS</tns:MasterToken>" +
                                "</tns:AuthAppInfo>" +
                            "</tns:AuthRequest>");
            getMessageRequest.Message = request.DocumentElement;
            // Получить сообщение
            OpenApiMessageConsumerServiceReference.GetMessageResponse getMessageResponse = client.GetMessage(getMessageRequest);
            // Получить сообщение соответствующе AuthResponse, описанное в AuthService-types-v0.1.xsd
            XmlElement response = getMessageResponse.Message;
            // Вывести в консоль полученный xml
            Console.WriteLine(response.OuterXml);

            // В случае успешного выполнения будет возвращен следующий xml:

//            <tns:AuthResponse xmlns:tns="urn://x-artefacts-gnivc-ru/ais3/kkt/AuthService/types/1.0" >
//	            <tns:Result>
//		            <tns:Token>TEMPORARY_TOKEN_ISSUED_BY_FNS</tns:Token>
//		            <tns:ExpireTime>2001-12-17T09:30:47Z</tns:ExpireTime>
//	            </tns:Result>
//            </tns:AuthResponse>

             // В случае ошибки будет возвращен следующий xml:

//            <tns:AuthResponse xmlns:tns="urn://x-artefacts-gnivc-ru/ais3/kkt/AuthService/types/1.0" >
//	            <tns:Fault>
//		            <tns:Message>Описание ошибки сервиса</tns:Message>
//	            </tns:Fault>
//            </tns:AuthResponse>

           // Который может быть десериализован в сгенерированные на основе xsd схемы типы
        }

        // Пример использования асинхронного сервиса сообщений открытых API на примере сервиса ККТ.
        // Пример полностью работоспособный.
        // К проекту прикреплен файл OpenApiAsyncMessageConsumerService-v0-1-soapui-project.xml, 
        // который можно ипортировать в SoapUI и запустить OpenApiAsyncMessageConsumerServiceSoapBinding MockService.
        // Для того чтобы MockService не подвис (глюк SoapUI), рекомендуется его запустить, остановить и снова запустить.
        static void OpenApiAsyncMessageConsumerServiceClientExample()
        {
            // Создание клиента
            OpenApiAsyncMessageConsumerServiceReference.OpenApiAsyncMessageConsumerServicePortTypeClient client = new OpenApiAsyncMessageConsumerServiceReference.OpenApiAsyncMessageConsumerServicePortTypeClient();

            using (new OperationContextScope(client.InnerChannel))
            {
                // Установить заголовки
                HttpRequestMessageProperty requestMessage = new HttpRequestMessageProperty();
                // Временный токен полученный в результате вызова синхронного сервиса аутентификации AuthResponse/Result/Token см. OpenApiMessageConsumerServiceClientExample
                requestMessage.Headers["FNS-OpenApi-Token"] = "TEMPORARY_TOKEN_ISSUED_BY_FNS";
                // Токен, уникально идентифицирующий пользователя в рамках внешнего пользователя (приложения),  в base64.
                // Длина токена должна быть <= 160 символов в base64, т.е. requestMessage.Headers["FNS-OpenApi-UserToken"].Length <= 160
                requestMessage.Headers["FNS-OpenApi-UserToken"] = Convert.ToBase64String(Encoding.UTF8.GetBytes("APP_USER_ID_1"));
                OperationContext.Current.OutgoingMessageProperties[HttpRequestMessageProperty.Name] = requestMessage;

                // Создание запроса
                OpenApiAsyncMessageConsumerServiceReference.SendMessageRequest sendMessageRequest = new OpenApiAsyncMessageConsumerServiceReference.SendMessageRequest();
                // В поле Message, необходимо добавить сообщение в соответсвии с элементом CheckTicketRequest, описанным в KktService-types-v0.1.xsd
                // Причем, в обязательном порядке должно быть указано пространство имен, и все элементы должны быть сформированы с учетом данного пространства имен
                // Как вариант сгенерировать типы на основе xsd схемы, и сериализовать их в xml
                XmlDocument request = new XmlDocument();
                // Данный фрагмент Xml приведен для примера и не содержит полный Xml соответсвующий схеме
                request.LoadXml("<CheckTicketRequest xmlns=\"urn://x-artefacts-gnivc-ru/inplat/servin/OpenApiAsyncMessageConsumerService/types/1.0\"></CheckTicketRequest>");
                sendMessageRequest.Message = request.DocumentElement;
                // Отправить сообщение
                OpenApiAsyncMessageConsumerServiceReference.SendMessageResponse sendMessageResponse = client.SendMessage(sendMessageRequest);
                // Сохранить идентификатор сообщения для получения результата
                string messageId = sendMessageResponse.MessageId;
            
                // Таймаут ожидания ответа в миллисекундах
                const int responseTimeout = 5000;
                Stopwatch stopwatch = Stopwatch.StartNew();
                // Вызов метода сервиса на периодической основе для получения результата выполнения запроса (не чаще чем 2 запроса/сек)
                while (true)
                {
                    if (stopwatch.ElapsedMilliseconds > responseTimeout)
                    {
                        stopwatch.Stop();
                        throw new TimeoutException("Превышено время ожидания ответа.");
                    }

                    // Время ожидания ответа в миллисекундах
                    const int responseWaitTime = 500;

                    // Thread.Sleep для упрощения примера, должно использоваться неблокирующее поток ожидание выполнения запроса
                    Thread.Sleep(responseWaitTime);
                    OpenApiAsyncMessageConsumerServiceReference.GetMessageRequest getMessageRequest = new OpenApiAsyncMessageConsumerServiceReference.GetMessageRequest();
                    // Присвоение сохраненного ранее идентификатора сообщения
                    getMessageRequest.MessageId = messageId;
                    try
                    {
                        // Получить сообщение
                        OpenApiAsyncMessageConsumerServiceReference.GetMessageResponse getMessageResponse = client.GetMessage(getMessageRequest);
                        // Если статус выполнения запроса COMPLETED - запрос выполнен, иначе запрос PROCESSING - выполняется 
                        if (getMessageResponse.ProcessingStatus == OpenApiAsyncMessageConsumerServiceReference.ProcessingStatuses.COMPLETED)
                        {
                            // Получить сообщение соответствующе CheckTicketResponse, описанное в KktService-types-v0.1.xsd
                            XmlElement response = getMessageResponse.Message;
                            // Вывести в консоль полученный xml
                            Console.WriteLine(response.OuterXml);
                            // Прервать вызов метода сервиса на периодической основе, после получения результата
                            break;
                        }
                    }
                    catch (FaultException<OpenApiAsyncMessageConsumerServiceReference.AuthenticationFault> ex)
                    {
                        // Переданы неверные аутентификационные реквизиты, либо что закончилось время их действия
                        throw;
                    }
                    catch (FaultException<OpenApiAsyncMessageConsumerServiceReference.MessageNotFoundFault> ex)
                    {
                        // Ответ на исходное сообщение не был получен вовремя, и был удален из хранилища ответов
                        throw;
                    }
                }
            }
        }
    }
}
