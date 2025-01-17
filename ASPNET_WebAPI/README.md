# ASPNET core Web API

**Author : Gan Zhi Ken**

This web API contains chatbot and dummy payment gateway (for cards payment).

Dummy payment gateway consist of 1 Views, that let users to input the cards information.

> **Note** : This is a **.NET 8** project

## Use Guide

1. Open Terminal **OR** Package Manager Console in Visual Studio 2022 

   - Ensure the path is same as project path (`ASPNET_WebAPI.csproj`)

2. Run migration files to create SQLite file.

   - `dotnet ef database update` 

3. Download AI model (Optional, If you wish to test the chatbot API)

   - Download [`llama-2-7b-chat.Q4_0.gguf`](https://huggingface.co/TheBloke/Llama-2-7B-Chat-GGUF/tree/main?show_file_info=llama-2-7b-chat.Q4_0.gguf) from hugging face
   - Place the model into `Assets` Folder 

4. To Test the API : 

   - Run Project in development environment
   - Navigate to `http://localhost:5096/swagger` in browser
   - Input the API key before use it, at the top right (**Authorize** button)
     - Chatbot : `fakeapikeyforchatbot_==`
     - Payment Gateway : `fakeapikeyforpayment_==`

   

