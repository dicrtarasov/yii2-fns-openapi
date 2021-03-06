﻿//------------------------------------------------------------------------------
// <auto-generated>
//     This code was generated by a tool.
//     Runtime Version:4.0.30319.42000
//
//     Changes to this file may cause incorrect behavior and will be lost if
//     the code is regenerated.
// </auto-generated>
//------------------------------------------------------------------------------

namespace open_api_client.OpenApiAsyncMessageConsumerServiceReference {
    
    
    /// <remarks/>
    [System.CodeDom.Compiler.GeneratedCodeAttribute("System.Xml", "4.6.1064.2")]
    [System.SerializableAttribute()]
    [System.Diagnostics.DebuggerStepThroughAttribute()]
    [System.ComponentModel.DesignerCategoryAttribute("code")]
    [System.Xml.Serialization.XmlTypeAttribute(Namespace="urn://x-artefacts-gnivc-ru/inplat/servin/OpenApiAsyncMessageConsumerService/types" +
        "/1.0")]
    public partial class AuthenticationFault : object, System.ComponentModel.INotifyPropertyChanged {
        
        public event System.ComponentModel.PropertyChangedEventHandler PropertyChanged;
        
        protected void RaisePropertyChanged(string propertyName) {
            System.ComponentModel.PropertyChangedEventHandler propertyChanged = this.PropertyChanged;
            if ((propertyChanged != null)) {
                propertyChanged(this, new System.ComponentModel.PropertyChangedEventArgs(propertyName));
            }
        }
    }
    
    /// <remarks/>
    [System.CodeDom.Compiler.GeneratedCodeAttribute("System.Xml", "4.6.1064.2")]
    [System.SerializableAttribute()]
    [System.Diagnostics.DebuggerStepThroughAttribute()]
    [System.ComponentModel.DesignerCategoryAttribute("code")]
    [System.Xml.Serialization.XmlTypeAttribute(Namespace="urn://x-artefacts-gnivc-ru/inplat/servin/OpenApiAsyncMessageConsumerService/types" +
        "/1.0")]
    public partial class MessageNotFoundFault : object, System.ComponentModel.INotifyPropertyChanged {
        
        public event System.ComponentModel.PropertyChangedEventHandler PropertyChanged;
        
        protected void RaisePropertyChanged(string propertyName) {
            System.ComponentModel.PropertyChangedEventHandler propertyChanged = this.PropertyChanged;
            if ((propertyChanged != null)) {
                propertyChanged(this, new System.ComponentModel.PropertyChangedEventArgs(propertyName));
            }
        }
    }
    
    [System.CodeDom.Compiler.GeneratedCodeAttribute("System.ServiceModel", "4.0.0.0")]
    [System.ServiceModel.ServiceContractAttribute(Namespace="urn://x-artefacts-gnivc-ru/inplat/servin/OpenApiAsyncMessageConsumerService/1.0", ConfigurationName="OpenApiAsyncMessageConsumerServiceReference.OpenApiAsyncMessageConsumerServicePor" +
        "tType")]
    public interface OpenApiAsyncMessageConsumerServicePortType {
        
        [System.ServiceModel.OperationContractAttribute(Action="urn:GetMessageRequest", ReplyAction="*")]
        [System.ServiceModel.FaultContractAttribute(typeof(open_api_client.OpenApiAsyncMessageConsumerServiceReference.AuthenticationFault), Action="urn:GetMessageRequest", Name="AuthenticationFault", Namespace="urn://x-artefacts-gnivc-ru/inplat/servin/OpenApiAsyncMessageConsumerService/types" +
            "/1.0")]
        [System.ServiceModel.FaultContractAttribute(typeof(open_api_client.OpenApiAsyncMessageConsumerServiceReference.MessageNotFoundFault), Action="urn:GetMessageRequest", Name="MessageNotFoundFault", Namespace="urn://x-artefacts-gnivc-ru/inplat/servin/OpenApiAsyncMessageConsumerService/types" +
            "/1.0")]
        [System.ServiceModel.XmlSerializerFormatAttribute(SupportFaults=true)]
        open_api_client.OpenApiAsyncMessageConsumerServiceReference.GetMessageResponse GetMessage(open_api_client.OpenApiAsyncMessageConsumerServiceReference.GetMessageRequest request);
        
        [System.ServiceModel.OperationContractAttribute(Action="urn:SendMessageRequest", ReplyAction="*")]
        [System.ServiceModel.FaultContractAttribute(typeof(open_api_client.OpenApiAsyncMessageConsumerServiceReference.AuthenticationFault), Action="urn:SendMessageRequest", Name="AuthenticationFault", Namespace="urn://x-artefacts-gnivc-ru/inplat/servin/OpenApiAsyncMessageConsumerService/types" +
            "/1.0")]
        [System.ServiceModel.XmlSerializerFormatAttribute(SupportFaults=true)]
        open_api_client.OpenApiAsyncMessageConsumerServiceReference.SendMessageResponse SendMessage(open_api_client.OpenApiAsyncMessageConsumerServiceReference.SendMessageRequest request);
    }
    
    /// <remarks/>
    [System.CodeDom.Compiler.GeneratedCodeAttribute("System.Xml", "4.6.1064.2")]
    [System.SerializableAttribute()]
    [System.Xml.Serialization.XmlTypeAttribute(Namespace="urn://x-artefacts-gnivc-ru/inplat/servin/OpenApiAsyncMessageConsumerService/types" +
        "/1.0")]
    public enum ProcessingStatuses {
        
        /// <remarks/>
        PROCESSING,
        
        /// <remarks/>
        COMPLETED,
    }
    
    [System.Diagnostics.DebuggerStepThroughAttribute()]
    [System.CodeDom.Compiler.GeneratedCodeAttribute("System.ServiceModel", "4.0.0.0")]
    [System.ServiceModel.MessageContractAttribute(WrapperName="GetMessageRequest", WrapperNamespace="urn://x-artefacts-gnivc-ru/inplat/servin/OpenApiAsyncMessageConsumerService/types" +
        "/1.0", IsWrapped=true)]
    public partial class GetMessageRequest {
        
        [System.ServiceModel.MessageBodyMemberAttribute(Namespace="urn://x-artefacts-gnivc-ru/inplat/servin/OpenApiAsyncMessageConsumerService/types" +
            "/1.0", Order=0)]
        public string MessageId;
        
        public GetMessageRequest() {
        }
        
        public GetMessageRequest(string MessageId) {
            this.MessageId = MessageId;
        }
    }
    
    [System.Diagnostics.DebuggerStepThroughAttribute()]
    [System.CodeDom.Compiler.GeneratedCodeAttribute("System.ServiceModel", "4.0.0.0")]
    [System.ServiceModel.MessageContractAttribute(WrapperName="GetMessageResponse", WrapperNamespace="urn://x-artefacts-gnivc-ru/inplat/servin/OpenApiAsyncMessageConsumerService/types" +
        "/1.0", IsWrapped=true)]
    public partial class GetMessageResponse {
        
        [System.ServiceModel.MessageBodyMemberAttribute(Namespace="urn://x-artefacts-gnivc-ru/inplat/servin/OpenApiAsyncMessageConsumerService/types" +
            "/1.0", Order=0)]
        public open_api_client.OpenApiAsyncMessageConsumerServiceReference.ProcessingStatuses ProcessingStatus;
        
        [System.ServiceModel.MessageBodyMemberAttribute(Namespace="urn://x-artefacts-gnivc-ru/inplat/servin/OpenApiAsyncMessageConsumerService/types" +
            "/1.0", Order=1)]
        public System.Xml.XmlElement Message;
        
        public GetMessageResponse() {
        }
        
        public GetMessageResponse(open_api_client.OpenApiAsyncMessageConsumerServiceReference.ProcessingStatuses ProcessingStatus, System.Xml.XmlElement Message) {
            this.ProcessingStatus = ProcessingStatus;
            this.Message = Message;
        }
    }
    
    [System.Diagnostics.DebuggerStepThroughAttribute()]
    [System.CodeDom.Compiler.GeneratedCodeAttribute("System.ServiceModel", "4.0.0.0")]
    [System.ServiceModel.MessageContractAttribute(WrapperName="SendMessageRequest", WrapperNamespace="urn://x-artefacts-gnivc-ru/inplat/servin/OpenApiAsyncMessageConsumerService/types" +
        "/1.0", IsWrapped=true)]
    public partial class SendMessageRequest {
        
        [System.ServiceModel.MessageBodyMemberAttribute(Namespace="urn://x-artefacts-gnivc-ru/inplat/servin/OpenApiAsyncMessageConsumerService/types" +
            "/1.0", Order=0)]
        public System.Xml.XmlElement Message;
        
        public SendMessageRequest() {
        }
        
        public SendMessageRequest(System.Xml.XmlElement Message) {
            this.Message = Message;
        }
    }
    
    [System.Diagnostics.DebuggerStepThroughAttribute()]
    [System.CodeDom.Compiler.GeneratedCodeAttribute("System.ServiceModel", "4.0.0.0")]
    [System.ServiceModel.MessageContractAttribute(WrapperName="SendMessageResponse", WrapperNamespace="urn://x-artefacts-gnivc-ru/inplat/servin/OpenApiAsyncMessageConsumerService/types" +
        "/1.0", IsWrapped=true)]
    public partial class SendMessageResponse {
        
        [System.ServiceModel.MessageBodyMemberAttribute(Namespace="urn://x-artefacts-gnivc-ru/inplat/servin/OpenApiAsyncMessageConsumerService/types" +
            "/1.0", Order=0)]
        public string MessageId;
        
        public SendMessageResponse() {
        }
        
        public SendMessageResponse(string MessageId) {
            this.MessageId = MessageId;
        }
    }
    
    [System.CodeDom.Compiler.GeneratedCodeAttribute("System.ServiceModel", "4.0.0.0")]
    public interface OpenApiAsyncMessageConsumerServicePortTypeChannel : open_api_client.OpenApiAsyncMessageConsumerServiceReference.OpenApiAsyncMessageConsumerServicePortType, System.ServiceModel.IClientChannel {
    }
    
    [System.Diagnostics.DebuggerStepThroughAttribute()]
    [System.CodeDom.Compiler.GeneratedCodeAttribute("System.ServiceModel", "4.0.0.0")]
    public partial class OpenApiAsyncMessageConsumerServicePortTypeClient : System.ServiceModel.ClientBase<open_api_client.OpenApiAsyncMessageConsumerServiceReference.OpenApiAsyncMessageConsumerServicePortType>, open_api_client.OpenApiAsyncMessageConsumerServiceReference.OpenApiAsyncMessageConsumerServicePortType {
        
        public OpenApiAsyncMessageConsumerServicePortTypeClient() {
        }
        
        public OpenApiAsyncMessageConsumerServicePortTypeClient(string endpointConfigurationName) : 
                base(endpointConfigurationName) {
        }
        
        public OpenApiAsyncMessageConsumerServicePortTypeClient(string endpointConfigurationName, string remoteAddress) : 
                base(endpointConfigurationName, remoteAddress) {
        }
        
        public OpenApiAsyncMessageConsumerServicePortTypeClient(string endpointConfigurationName, System.ServiceModel.EndpointAddress remoteAddress) : 
                base(endpointConfigurationName, remoteAddress) {
        }
        
        public OpenApiAsyncMessageConsumerServicePortTypeClient(System.ServiceModel.Channels.Binding binding, System.ServiceModel.EndpointAddress remoteAddress) : 
                base(binding, remoteAddress) {
        }
        
        public open_api_client.OpenApiAsyncMessageConsumerServiceReference.GetMessageResponse GetMessage(open_api_client.OpenApiAsyncMessageConsumerServiceReference.GetMessageRequest request) {
            return base.Channel.GetMessage(request);
        }
        
        public open_api_client.OpenApiAsyncMessageConsumerServiceReference.SendMessageResponse SendMessage(open_api_client.OpenApiAsyncMessageConsumerServiceReference.SendMessageRequest request) {
            return base.Channel.SendMessage(request);
        }
    }
}
