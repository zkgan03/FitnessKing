using ASPNET_WebAPI.Authentication;
using ASPNET_WebAPI.Models.Dtos.Chat;
using ASPNET_WebAPI.Services.ChatServices;
using LLama.Common;
using Microsoft.AspNetCore.Mvc;

namespace ASPNET_WebAPI.Controllers
{
    [ApiController]
    [Route("api/[controller]")]
    [ServiceFilter(typeof(ApiKeyAuthFilter))]
    public class ChatController : ControllerBase
    {
        private readonly ILogger<ChatController> _logger;

        public ChatController(ILogger<ChatController> logger)
        {
            _logger = logger;
        }

        [HttpPost("History/Stream")]
        public async Task SendHistoryStream([FromBody] HistoryInput input, [FromServices] ChatService _service, CancellationToken cancellationToken)
        {

            var history = new ChatHistory();
            var messages = input.Messages.Select(m => new ChatHistory.Message(Enum.Parse<AuthorRole>(m.Role), m.Content));
            history.Messages.AddRange(messages);

            Response.ContentType = "text/event-stream";

            await foreach (var r in _service.SendStream(history))
            {
                await Response.WriteAsync(r + "\x1E", cancellationToken);
                await Response.Body.FlushAsync(cancellationToken);
            }

            await Response.CompleteAsync();
        }

        [HttpPost("History")]
        public async Task<string> SendHistory([FromBody] HistoryInput input, [FromServices] ChatService _service)
        {
            var history = new ChatHistory();

            // Convert the input messages to ChatHistory.Message
            var messages = input.Messages.Select(m => new ChatHistory.Message(Enum.Parse<AuthorRole>(m.Role), m.Content));

            history.Messages.AddRange(messages);

            return await _service.SendAsync(history);
        }

    }
}